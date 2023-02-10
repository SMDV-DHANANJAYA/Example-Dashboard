<?php

use App\Models\User;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('mobile')->nullable();
            $table->date('birthday')->nullable();
            $table->text('address')->nullable();
            $table->string('emergency_contact_number')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->text('photo_id_path')->nullable();
            $table->text('police_check_path')->nullable();
            $table->text('wwcc_path')->nullable();
            $table->tinyInteger('state')->default(User::ACTIVE)->comment(User::DE_ACTIVE . ' => De_active | ' . User::ACTIVE . ' => Active');
            $table->tinyInteger('type')->comment(User::SUPER_ADMIN . ' => Super Admin | ' . User::ADMIN . ' => Admin | ' . User::USER . ' => User');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->tinyInteger('login_state')->nullable()->comment(User::LOGOUT . ' => Logout | ' . User::LOGIN . ' => Login');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
