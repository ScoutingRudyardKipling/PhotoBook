<?php

namespace App\Models\Staf;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Staf\StafUser
 *
 * @property      int $UserId
 * @property      string $Firstname
 * @property      string $Initials
 * @property      string $Middlename
 * @property      string $Lastname
 * @property      string $Street
 * @property      string $StreetNumber
 * @property      string $StreetNumberExtra
 * @property      string $Zip
 * @property      string $City
 * @property      string $Country
 * @property      string $Email
 * @property      string $EmailParentOne
 * @property      string $EmailParentTwo
 * @property      string $Gender
 * @property      string $BirthDate
 * @property      string $MobilePhone
 * @property      string $Phone
 * @property      \Illuminate\Support\Carbon $created_at
 * @property      \Illuminate\Support\Carbon|null $updated_at
 * @property      \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $full_name
 * @method        static bool|null forceDelete()
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Staf\StafUser newModelQuery()
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Staf\StafUser newQuery()
 * @method        static \Illuminate\Database\Query\Builder|\App\Models\Staf\StafUser onlyTrashed()
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Staf\StafUser query()
 * @method        static bool|null restore()
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Staf\StafUser whereBirthDate($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Staf\StafUser whereCity($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Staf\StafUser whereCountry($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Staf\StafUser whereCreatedAt($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Staf\StafUser whereDeletedAt($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Staf\StafUser whereEmail($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Staf\StafUser whereEmailParentOne($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Staf\StafUser whereEmailParentTwo($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Staf\StafUser whereFirstname($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Staf\StafUser whereGender($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Staf\StafUser whereInitials($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Staf\StafUser whereLastname($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Staf\StafUser whereMiddlename($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Staf\StafUser whereMobilePhone($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Staf\StafUser wherePhone($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Staf\StafUser whereStreet($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Staf\StafUser whereStreetNumber($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Staf\StafUser whereStreetNumberExtra($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Staf\StafUser whereUpdatedAt($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Staf\StafUser whereUserId($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Staf\StafUser whereZip($value)
 * @method        static \Illuminate\Database\Query\Builder|\App\Models\Staf\StafUser withTrashed()
 * @method        static \Illuminate\Database\Query\Builder|\App\Models\Staf\StafUser withoutTrashed()
 * @mixin         \Eloquent
 */
class StafUser extends Model
{
    use SoftDeletes;

    protected $connection = "mysql_staf";
    protected $table = 'User';

    protected $fillable = [
        'UserId',
        'Firstname',
        'Initials',
        'Middlename',
        'Lastname',
        'Street',
        'StreetNumber',
        'StreetNumberExtra',
        'Zip',
        'City',
        'Country',
        'Email',
        'EmailParentOne',
        'EmailParentTwo',
        'Gender',
        'Birthdate',
        'MobilePhone',
        'Phone',
    ];

    protected $casts = [
        'Birthdate' => 'date',
    ];

    protected $appends = [
        'Alias',
        'FullName',
        'StrippedEmail',
        'StrippedEmailParentOne',
        'StrippedEmailParentTwo',
    ];

    public static function stripEmailAddress($emailAddress)
    {
        return strtolower(preg_replace(['/(\.)(?=.*\@gmail.com)/', '/(\+.*)(?=\@)/'], '', $emailAddress));
    }

    public function getFullNameAttribute()
    {
        $tv = ' ' . $this->Middlename . ' ';
        if (strlen($this->Middlename) == 0) {
            $tv = ' ';
        }
        return $this->Firstname . $tv . $this->Lastname;
    }
}
