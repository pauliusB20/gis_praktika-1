<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        //55.925867515974545, 23.32807203068907
        \App\GeoLocation::create([
            "longitude" => 23.32807203068907, //open - true, close - false
            "latitude" => 55.925867515974545
        ]);

        \App\Muzeum::create([
            "ins_name" => "Šiaulių Aušros muziejus",
            "status" => true, //open - true, close - false
            "closingtime" => 0,
            "address" => "Vilniaus g. 74, Šiauliai 76283",
            "description" => "Šiaulių „Aušros“ muziejus – vienas iš didžiausių ir aktyviausių Lietuvos muziejų...",
            "geo_id" => \App\GeoLocation::max('id')
        ]);
        ///------------------------------------------------
        //55.92872657099051, 23.319880546033605
        \App\GeoLocation::create([
            "longitude" => 23.319880546033605, //open - true, close - false
            "latitude" => 55.92872657099051
        ]);

        \App\Muzeum::create([
            "ins_name" => "Šiaulių Dviračių muziejus",
            "status" => true, //open - true, close - false
            "closingtime" => 0,
            "address" => "Vilniaus g. 137A, Šiauliai 76353",
            "description" => "Dviračių muziejus – vienintelis specializuotas dviračių tematikai skirtas Lietuvos muziejus,
                              kuriame eksponuojama daugiau nei 67 dviračiai, 4 velomobiliai ir apie 222 kitų eksponatų.",
            "geo_id" => \App\GeoLocation::max('id')
        ]);
         ///------------------------------------------------
         //55.930287614522754, 23.311353907932787
        \App\GeoLocation::create([
            "longitude" => 23.311353907932787, //open - true, close - false
            "latitude" => 55.930287614522754
        ]);

        \App\Muzeum::create([
            "ins_name" => "'Rūtos' šokolado muziejus",
            "status" => false, //open - true, close - false
            "closingtime" => 0,
            "address" => "Tilžės g. 133, Šiauliai 76349",
            "description" => "2012 m. birželio 1 d. autentiškas XX a. trečiojo dešimtmečio saldainių fabriko „Rūta“ pastatas atgijo – legendomis ir
                              svajomis apipinta vieta atvėrė duris miestiečiams ir atvykstantiems svečiams.",
            "geo_id" => \App\GeoLocation::max('id')
        ]);
    }
}
