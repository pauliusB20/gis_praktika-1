<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Muzeum extends Model
{
    public $timestamps = false;
    protected $table = "muzeum";

    protected $fillable = [
        'id',
        'ins_name',
        'status',
        'closingtime',
        'address',
        'description',
        'image_url',
        'geo_id'
    ];
}
