<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    protected $fillable = [
        'name', 'amount', 'promotion', 'description', 'months'
    ];
}
