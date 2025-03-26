<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable();
            $table->text('address')->nullable(); 
            $table->string('role')->default(User::ROLE_USER);
            $table->string('google_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}