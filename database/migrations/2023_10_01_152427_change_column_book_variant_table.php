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
        Schema::table('book_variants', function (Blueprint $table) {
            $table->integer('stock')->default(0)->change();
            $table->decimal('price', 15, 2)->nullable()->default(0)->change();
            $table->decimal('cost', 15, 2)->nullable()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('book_variants', function (Blueprint $table) {
            //
        });
    }
};
