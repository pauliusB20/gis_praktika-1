<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class MDataCollector extends Controller
{
    public function getMuzData()
    {
        //Collecting information from the app database table
        $geoLocations = \App\GeoLocation::all();
        $muzBuildingData = \App\Muzeum::all();

        foreach($muzBuildingData as $building)
        {
            foreach($geoLocations as $loc)
            {
                if ($building->geo_id == $loc->id)
                {
                    $building["long"] = $loc->longitude;
                    $building["lat"] = $loc->latitude;
                }
            }
        }
        $muzBuildingData->toArray();
        return view('main_app_page', [
                                      'buildings' => $muzBuildingData
                                     ]);
    }
}
