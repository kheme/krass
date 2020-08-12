<?php
/**
 * User model
 *
 * PHP version 7
 *
 * @category  Model
 * @package   App\Http\Models
 * @author    Okiemute Omuta <iamkheme@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights retained
 * @link      https://twitter.com/kheme
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

/**
 * Main User class
 *
 * @category  Model
 * @package   App\Http\Models
 * @author    Okiemute Omuta <iamkheme@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights retained
 * @link      https://twitter.com/kheme
 */
class User extends Model
{
    use HasApiTokens;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden   = [
        'password',
    ];

    /**
     * Hash a user's password
     *
     * @param string $value Password
     * 
     * @author Okiemute Omuta <iamkheme@gmail.com>
     * 
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Proper casing for user's name
     *
     * @param string $value Name
     * 
     * @author Okiemute Omuta <iamkheme@gmail.com>
     * 
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = mb_convert_case($value, MB_CASE_TITLE);
    }

    /**
     * Proper casing for user's email
     *
     * @param string $value Email address
     * 
     * @author Okiemute Omuta <iamkheme@gmail.com>
     * 
     * @return Collection
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = mb_convert_case($value, MB_CASE_LOWER);
    }

    /**
     * A user has many transfers
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     *
     * @return Collection
     */
    public function getTransfers()
    {
        return $this->hasMany(Transfer::class, 'user_id');
    }
}
