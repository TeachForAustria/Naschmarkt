<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * The social logins table is used to associate users to social network accounts.
 * @package App
 */
class SocialLogin extends Model
{
    /**
     * Relationship to the user.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
