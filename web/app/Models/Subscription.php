<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Sumra\SDK\Traits\UuidTrait;

/**
 * Class Subscription
 *
 * @package App\Models
 */
class Subscription extends Model
{
    use HasFactory;
    use UuidTrait;

    /**
     * Type constants
     */
    const TYPE_SEARCH = 'search';
    const TYPE_ITEM = 'item';

    /**
     * Type of item objects
     */
    const ITEM_ADVERT = 'advert';
    const ITEM_NEWS = 'news';
    const ITEM_WAREHOUSE = 'warehouse';

    /**
     * Status constants
     */
    const STATUS_ACTIVE = 1;
    const STATUS_IN_PROGRESS = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_PAUSED = 4;
    const STATUS_NOT_ACTIVE = 5;

    /**
     * Sending period
     */
    const PERIOD_HOURLY = 1;
    const PERIOD_DAILY = 1;
    const PERIOD_WEEKLY = 7;
    const PERIOD_MONTHLY = 30;

    /**
     * @var string[]
     */
    public static $types = [
        self::TYPE_SEARCH,
        self::TYPE_ITEM
    ];

    /**
     * @var int[]
     */
    public static $statuses = [
        self::STATUS_ACTIVE,
        self::STATUS_IN_PROGRESS,
        self::STATUS_COMPLETED,
        self::STATUS_PAUSED,
        self::STATUS_NOT_ACTIVE
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'type',
        'period',
        'status'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function parameters()
    {
        return $this->hasMany('App\Models\SubscriptionParameter');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany('App\Models\SubscriptionItem');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function channels()
    {
        return $this->hasMany('App\Models\SubscriptionChannel');
    }
}
