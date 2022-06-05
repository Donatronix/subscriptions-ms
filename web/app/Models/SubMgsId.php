<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubMgsId extends Model
{
    protected $table = 'sub_mgs_ids';

    protected $fillable = [
        'subscriber_id',
        'message_id',
        'status'
    ];

    protected $guarded = [];
}
