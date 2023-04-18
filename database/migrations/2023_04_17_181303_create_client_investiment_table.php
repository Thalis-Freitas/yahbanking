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
        Schema::create('client_investiment', function (Blueprint $table) {
            $table->foreignId('client_id')->constrained();
            $table->foreignId('investiment_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('invested_value', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_investiment');
    }
};
