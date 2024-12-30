<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('User_ID')->nullable();;
            $table->string('Email')->unique();
            $table->string('Password');
            $table->string('Employee_ID')->unique();
            $table->string('Fullname')->nullable();
            $table->string('Role')->default('Pengguna');
            $table->string('Profile_Picture')->nullable();
            $table->timestamp('Date_Created')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('Position')->nullable();
            $table->string('Department')->nullable();
            $table->string('Unit')->nullable();
            $table->string('Telephone')->nullable();
            $table->enum('Status_Aktif', ['0','1'])->default('1');
            $table->enum('needs_password_reset',['0','1'])->default('0');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
