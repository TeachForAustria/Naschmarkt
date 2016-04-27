<?php
/**
 * An helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\Document
 *
 * @property integer $id
 * @property integer $post_id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Post $post
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DocumentVersion[] $documentVersions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Tag[] $tags
 * @method static \Illuminate\Database\Query\Builder|\App\Document whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Document wherePostId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Document whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Document whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Document whereUpdatedAt($value)
 */
	class Document extends \Eloquent {}
}

namespace App{
/**
 * App\DocumentVersion
 *
 * @property integer $id
 * @property integer $version
 * @property integer $document_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $uuid
 * @property string $extension
 * @property-read \App\Document $document
 * @method static \Illuminate\Database\Query\Builder|\App\DocumentVersion whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\DocumentVersion whereVersion($value)
 * @method static \Illuminate\Database\Query\Builder|\App\DocumentVersion whereDocumentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\DocumentVersion whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\DocumentVersion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\DocumentVersion whereUuid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\DocumentVersion whereExtension($value)
 */
	class DocumentVersion extends \Eloquent {}
}

namespace App{
/**
 * App\Post
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $access_count
 * @property integer $owner_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Document[] $documents
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Tag[] $tags
 * @method static \Illuminate\Database\Query\Builder|\App\Post whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Post whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Post whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Post whereAccessCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Post whereOwnerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Post whereUpdatedAt($value)
 */
	class Post extends \Eloquent {}
}

namespace App{
/**
 * App\SocialLogin
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $provider
 * @property integer $user_id
 * @property string $provider_id
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\SocialLogin whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SocialLogin whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SocialLogin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SocialLogin whereProvider($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SocialLogin whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SocialLogin whereProviderId($value)
 */
	class SocialLogin extends \Eloquent {}
}

namespace App{
/**
 * App\Tag
 *
 * @property integer $id
 * @property string $value
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Post[] $documents
 * @method static \Illuminate\Database\Query\Builder|\App\Tag whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Tag whereValue($value)
 */
	class Tag extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property boolean $is_staff
 * @property string $activation_token
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Document[] $documents
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SocialLogin[] $socialLogins
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereIsStaff($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereActivationToken($value)
 */
	class User extends \Eloquent {}
}

