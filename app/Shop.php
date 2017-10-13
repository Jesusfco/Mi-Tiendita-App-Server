<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = [
        'name', 'cash', 'active', 'street', 'colony', 'postalCode', 'city', 'state', 'createBy', 'country'
    ];
}
