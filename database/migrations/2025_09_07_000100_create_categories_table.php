<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->enum('kind', ['expense', 'income']);
            $table->timestamps();
            $table->unique(['name', 'kind']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

