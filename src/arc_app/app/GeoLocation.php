<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeoLocation extends Model
{
    public $timestamps = false;
    protected $table = "geo_location";

    protected $fillable = [
        'id',
        'longitude',
        'latitude'
    ];
}
