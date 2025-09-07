<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('category_id')->constrained('categories');
            $table->decimal('amount', 14, 2);
            $table->date('received_at');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('received_at');
            $table->index('category_id');
            // Note: CHECK constraint not added here to keep portability
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
