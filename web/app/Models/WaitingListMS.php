<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class WaitingListMS extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'waiting_list_ms';

    protected $fillable = [
        'message',
    ];

    protected $guarded = [];

    public function submgId(){
        $this->hasMany('App\Models\SubMgsId', 'waiting_list_ms_id');
    }

    
}
