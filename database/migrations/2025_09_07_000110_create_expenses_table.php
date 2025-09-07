<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('category_id')->constrained('categories');
            $table->decimal('amount', 14, 2);
            $table->date('spent_at');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('spent_at');
            $table->index('category_id');
            // Note: CHECK constraint not added here to keep portability
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
