<?php

use App\Models\UserLocations;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('location_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->text('date')->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->double('area')->comment('Area distance in meters');
            $table->tinyInteger('type')->comment(UserLocations::ONETIME . ' => Onetime | ' . UserLocations::CUSTOMDAYS . ' => Custom Days | ' . UserLocations::EVERYDAY . ' => Every Day');
            $table->tinyInteger('state')->default(UserLocations::NOTSTART)->comment(UserLocations::NOTSTART . ' => Not start | ' . UserLocations::START . ' => Start | ' . UserLocations::END . ' => End');
            $table->integer('attendance_id')->nullable();
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
        Schema::dropIfExists('user_locations');
    }
};
