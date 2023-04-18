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
        Schema::table('clients', function (Blueprint $table) {
            $table->decimal('total_value', 10, 2)->default(0);
            $table->decimal('uninvested_value', 10, 2)->default(0);
            $table->decimal('invested_value', 10, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('total_value');
            $table->dropColumn('uninvested_value');
            $table->dropColumn('invested_value');
        });
    }
};
