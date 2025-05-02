<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeofencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geofences', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('coordinates');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('type', ['circle', 'polygon']);
            $table->text('radius')->nullable(); // This for storing the radius of the circle
            $table->text('center')->nullable(); // This for storing the center of the circle geofences
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('geofences');
    }
}
