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
            $table->id("id_pk_user");
            $table->string('user_nama');
            $table->string('user_email')->unique();
            $table->string('user_no_hp')->nullable();
            $table->string('user_password');
            $table->boolean('user_is_email_validated')->default(false)->nullable();
            $table->boolean('user_is_no_hp_validated')->default(false)->nullable();
            $table->timestamp('email_verified_at')->nullable();
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
