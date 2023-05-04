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
        Schema::rename('client_investiment', 'client_investment');
        Schema::table('client_investment', function (Blueprint $table) {
            $table->renameColumn('investiment_id', 'investment_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_investment', function (Blueprint $table) {
            $table->renameColumn('investment_id', 'investiment_id');
        });
        Schema::rename('client_investment', 'client_investiment');
    }
};
