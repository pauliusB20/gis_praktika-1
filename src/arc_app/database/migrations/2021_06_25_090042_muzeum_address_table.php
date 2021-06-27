<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MuzeumAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geo_location', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('longitude', 10, 8);
            $table->decimal('latitude', 10, 8);
        });

        Schema::create('muzeum', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ins_name');
            $table->boolean('status')->nullable();
            $table->bigInteger('closingtime')->nullable();
            $table->string('address');
            $table->text('description');
            $table->text('image_url');
            $table->bigInteger('geo_id')->references('id')->on('geo_location')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('muzeum_address');
        Schema::drop('geo_location');
    }
}
