<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;
use Carbon\Carbon;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory, UuidTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'platform',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'updated_at',
    ];

    /**
     *  Calculate the total number of users
     *
     * @return int
     */
    public static function getTotalUsers ()
    {
        return self::all()
            ->count();
    }

    /**
     *  Count the number of new users given by time
     *
     * @param string | $time
     * @return int
     */
    public static function getCountNewUserByTime ($time)
    {
        switch ($time)
        {
            case 'week' :
                return self::whereBetween('created_at', [Carbon::now()
                    ->startOfWeek(), Carbon::now()
                    ->endOfWeek()])
                    ->count();

            case 'month' :
                return self::whereBetween('created_at', [Carbon::now()
                    ->startOfMonth(), Carbon::now()
                    ->endOfMonth()])
                    ->count();
        }
    }
}
