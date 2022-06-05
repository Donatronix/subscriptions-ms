<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sumra\SDK\Traits\UuidTrait;

class Subscriber extends Model
{
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
        'channel'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Count the number of new subscribers given by time
     *
     * @param             $query
     * @param string|null $time
     *
     * @return mixed
     */
    public function scopeCountNewSubscriberByTime($query, string $time = null): mixed
    {
        return $query->whereBetween('created_at', $this->getPeriod($time));
    }

    /**
     * @param             $query
     * @param string|null $time
     *
     * @return mixed
     */
    public function scopeCountNewSubscribersByPlatform($query, string $time = null): mixed
    {
        return $this->scopeCountNewSubscriberByTime($query, $time)
            ->groupBy('platform')
            ->selectRaw('platform, count(*) as total');
    }

    /**
     * @param             $query
     * @param string|null $time
     *
     * @return mixed
     */
    public function scopeCountNewSubscribersByChannel($query, string $time = null): mixed
    {
        return $this->scopeCountNewSubscriberByTime($query, $time)
            ->groupBy('channel')
            ->selectRaw('channel, count(*) as total');
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
            'year' => [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
            ],
            default => [
                Carbon::now()->startOfDay(),
                Carbon::now()->endOfDay(),
            ],
        };
    }
}
