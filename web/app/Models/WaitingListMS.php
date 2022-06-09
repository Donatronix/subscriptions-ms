<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaitingListMS extends Model
{
    protected $table = 'waiting_list_ms';

    protected $fillable = [
        'message',
    ];

    protected $guarded = [];

    public function submgId(){
        $this->hasMany('App\Models\SubMgsId', 'waiting_list_ms_id');
    }

    
}
