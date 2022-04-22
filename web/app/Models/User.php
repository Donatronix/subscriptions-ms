<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;
use Carbon\Carbon;
use Sumra\SDK\Traits\UuidTrait;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable;
    use Authorizable;
    use HasFactory;
    use SoftDeletes;
    use UuidTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'platform',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'pivot',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Count the number of new users given by time
     *
     * @param             $query
     * @param string|null $time
     *
     * @return mixed
     */
    public function scopeCountNewUserByTime($query, string $time = null): mixed
    {
        return $query->whereBetween('created_at', $this->getPeriod($time));

    }

    /**
     * @param             $query
     * @param string|null $time
     *
     * @return mixed
     */
    public function scopeCountNewUsersByPlatform($query, string $time = null): mixed
    {
        return $query->whereBetween('created_at', $this->getPeriod($time))
            ->groupBy('platform')
            ->selectRaw('platform, count(*) as total');
    }


    /**
     * @param $time
     *
     * @return array
     */
    protected function getPeriod($time): array
    {
        return match ($time) {
            'week' => [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek(),
            ],
            'month' => [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth(),
            ],
            default => [
                Carbon::now()->startOfDay(),
                Carbon::now()->endOfDay(),
            ],
        };
    }
}
