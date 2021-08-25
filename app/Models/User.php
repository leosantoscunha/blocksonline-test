<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_name', 'user_password', 'user_email', 'age', 'registration_date'];

    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_password'
    ];

    public function address()
    {
        return $this->hasOne(UserAddress::class);
    }

    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (isset($this->attributes[$key])) {

            if ($key === 'registration_date') {
                $value = \Carbon\Carbon::createFromFormat(Carbon::DEFAULT_TO_STRING_FORMAT, $value)->format('d/m/Y');
            }

            if ($key === 'age') {
                $value = \Carbon\Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y');
            }

        }

        return $value;
    }

}
