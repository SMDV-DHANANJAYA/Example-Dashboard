<?php

use App\Models\Notification;
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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->tinyInteger('read_state')->comment(Notification::NOTIFICATION_NEW . ' => New | ' . Notification::NOTIFICATION_READ . ' => Read');
            $table->tinyInteger('state')->comment(Notification::DANGER . ' => Danger | ' . Notification::WARNING . ' => Warning | ' . Notification::SUCCESS . ' => Success');
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
        Schema::dropIfExists('notifications');
    }
};
