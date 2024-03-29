<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\User
 *
 * @property      int                                                                                                       $id
 * @property      string                                                                                                    $name
 * @property      string                                                                                                    $email
 * @property      \Illuminate\Support\Carbon|null                                                                           $email_verified_at
 * @property      string                                                                                                    $password
 * @property      string                                                                                                    $birth_date
 * @property      string                                                                                                    $gender
 * @property      string                                                                                                    $preferred_language
 * @property      string|null                                                                                               $remember_token
 * @property      int                                                                                                       $external_user
 * @property      \Illuminate\Support\Carbon|null                                                                           $created_at
 * @property      \Illuminate\Support\Carbon|null                                                                           $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null                                                                                                  $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[]                           $permissions
 * @property-read int|null                                                                                                  $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[]                                 $roles
 * @property-read int|null                                                                                                  $roles_count
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereExternalUser($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\User permission($permissions)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\User role($roles, $guard = null)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereBirthDate($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmailVerifiedAt($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereGender($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereName($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePreferredLanguage($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @mixin         \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'birth_date',
        'gender',
        'external_user',
        'preferred_language',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var string[]
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
