<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'total', 'user_id', 'created_at',
    ];

    public $timestamps = false;

}
