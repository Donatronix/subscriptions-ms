<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Sumra\SDK\Traits\UuidTrait;

class SubscriptionItem extends Model
{
    use HasFactory;
    use UuidTrait;
    /**
     * @var string[]
     */
    protected $fillable = [
        'subscription_id',
        'item_id',
        'item_object'
    ];

    public function subscription()
    {
        return $this->belongsTo('App\Models\Subscription');
    }
}
