<?php

namespace App;

use App\Jobs\GenerateKeywords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Posts represent resources uploaded by the user.
 * Each post has tags, a description as well as multiple documents.
 * @package App
 */
class Post extends Model
{
    use DispatchesJobs;

    /**
     * Relationship to the post's owner.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo('App\User', 'owner_id');
    }

    /**
     * Relationship to documents.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents()
    {
        return $this->hasMany('App\Document');
    }

    /**
     * Relationship to tags.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }


    public function folders()
    {
        return $this->belongsToMany('App\Folder');
    }

    /**
     * Synchronize the tags of the model with the given list of tags:
     * Remove all tags which do not appear in the list and add the missing tags.
     * @param $tags array list of tag values
     */
    public function syncTags($tags)
    {
        $tags = array_filter($tags, function($tag) {
            return trim($tag) !== '';
        });

        $tags = array_map(function($tag) {
            return Tag::firstOrCreate([
                'value' => $tag
            ])->id;
        }, $tags);
        $this->tags()->sync($tags);
    }

    /**
     * Synchronize the documents of the model with the given list of documents:
     * Remove all documents which do not appear in the list and add the missing tags.
     * @param $documents array list of documents
     */
    public function syncDocuments($documents)
    {
        $documentNames = array_map(function($document) {
            return $document['name'];
        }, $documents);
        $this->documents()->whereNotIn('name', $documentNames)->delete();

        $databaseDocuments = $this->documents()->get();
        foreach($documents as $document) {
            if(!$databaseDocuments->contains('name', $document['name'])) {
                $databaseDocument = new Document();
                $databaseDocument->name = $document['name'];
                $this->documents()->save($databaseDocument);

                self::assignDocumentToDocumentVersion($document['uuid'], $databaseDocument->id);

                $lastDocumentVersion = $databaseDocument->documentVersions()->get()->last();

                //save the extension
                $extension = $lastDocumentVersion->extension;

                //valid extensions where read method exists
                $checkExtension = array('doc', 'docx', 'pdf', 'txt', 'html');

                if (in_array($extension, $checkExtension)) {
                    $this->dispatch(new GenerateKeywords($lastDocumentVersion, $document));
                }

            } else {
                $databaseDocument = $databaseDocuments->where('name', $document['name'])->first();
                $lastDocumentVersion = $databaseDocument->documentVersions()->get()->last();
                if($lastDocumentVersion->uuid !== $document['uuid']) {
                    self::assignDocumentToDocumentVersion($document['uuid'], $databaseDocument->id, $lastDocumentVersion->version + 1);
                }

                //save the extension
                $extension = $lastDocumentVersion->extension;

                //valid extensions where read method exists
                $checkExtension = array('doc', 'docx', 'pdf', 'txt', 'html');

                if (in_array($extension, $checkExtension)) {
                    $databaseDocument->keywords()->detach();
                    $this->dispatch(new GenerateKeywords($lastDocumentVersion, $databaseDocument));
                }
            }
        }
    }

    /**
     * Description mutator
     * Clean the description of invalid tags before writing it to the model field.
     * @param $desc string description
     */
    public function setDescription($desc)
    {
        $this->attributes['description'] = clean($desc);
    }

    /**
     * Helper method of assigning a document document to a document version.
     * @param $uuid string document version uuid
     * @param $documentId int document id
     * @param int $version int document version (defaults to 0)
     */
    private static function assignDocumentToDocumentVersion($uuid, $documentId, $version = 0)
    {
        $documentVersion = DocumentVersion::whereUuid($uuid)->firstOrFail();
        print($documentVersion);
        if($documentVersion->document_id !== null)
            abort(403);
        $documentVersion->document_id = $documentId;
        if($documentVersion !== 0)
            $documentVersion->version = $version;
        $documentVersion->save();
    }
}
