<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_profile', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('profile_id');
            $table->timestamps();

            // Definir las claves foráneas
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('cascade');

            // Asegurarse de que cada usuario tenga un solo perfil único
            $table->unique(['user_id', 'profile_id']);
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('user_profile');
    }
};
