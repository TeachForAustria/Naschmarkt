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
 * App\ConcreteDocument
 *
 * @property integer $id
 * @property integer $version
 * @property integer $document_id
 * @property mixed $content
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Document $document
 * @method static \Illuminate\Database\Query\Builder|\App\ConcreteDocument whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ConcreteDocument whereVersion($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ConcreteDocument whereDocumentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ConcreteDocument whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ConcreteDocument whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ConcreteDocument whereUpdatedAt($value)
 */
	class ConcreteDocument extends \Eloquent {}
}

namespace App{
/**
 * App\Document
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $access_count
 * @property integer $owner_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ConcreteDocument[] $concreteDocuments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Tag[] $tags
 * @method static \Illuminate\Database\Query\Builder|\App\Document whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Document whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Document whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Document whereAccessCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Document whereOwnerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Document whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Document whereUpdatedAt($value)
 */
	class Document extends \Eloquent {}
}

namespace App{
/**
 * App\DocumentTag
 *
 */
	class DocumentTag extends \Eloquent {}
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Document[] $documents
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

