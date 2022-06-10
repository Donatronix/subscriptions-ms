<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sumra\SDK\Traits\UuidTrait;

class SubMgsId extends Model
{
    use HasFactory;
    use SoftDeletes;
    use UuidTrait;

    protected $table = 'sub_mgs_ids';

    protected $fillable = [
        'subscriber_ids',
        'message_id',
        'status',
        'failed_message'
    ];
}
