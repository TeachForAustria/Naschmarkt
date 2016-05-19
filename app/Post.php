<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function owner()
    {
        return $this->belongsTo('App\User', 'owner_id');
    }

    public function documents()
    {
        return $this->hasMany('App\Document');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    public function syncTags($tags)
    {
        $tags = array_map(function($tag) {
            return Tag::firstOrCreate([
                'value' => $tag
            ])->id;
        }, $tags);
        $this->tags()->sync($tags);
    }

    public function syncDocuments($documents)
    {
        $documentNames = array_map(function($document) {
            return $document['name'];
        }, $documents);
        $this->documents()->whereNotIn('name', $documentNames)->delete();

        $databaseDocuments = $this->documents()->get();
        foreach($documents as $document) {
            print('<br>');
            if(!$databaseDocuments->contains('name', $document['name'])) {
                $databaseDocument = new Document();
                $databaseDocument->name = $document['name'];
                $this->documents()->save($databaseDocument);

                self::assignDocumentToDocumentVersion($document['uuid'], $databaseDocument->id);
            } else {
                $databaseDocument = $databaseDocuments->where('name', $document['name'])->first();
                $lastDocumentVersion = $databaseDocument->documentVersions()->get()->last();
                if($lastDocumentVersion->uuid !== $document['uuid']) {
                    self::assignDocumentToDocumentVersion($document['uuid'], $databaseDocument->id, $lastDocumentVersion->version + 1);
                }
            }
        }
    }

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
