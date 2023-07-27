<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToPlatePrintItemsTable extends Migration
{
    public function up()
    {
        Schema::table('plate_print_items', function (Blueprint $table) {
            $table->unsignedBigInteger('plate_print_id')->nullable();
            $table->foreign('plate_print_id', 'plate_print_fk_8797631')->references('id')->on('plate_prints');
            $table->unsignedBigInteger('semester_id')->nullable();
            $table->foreign('semester_id', 'semester_fk_8797632')->references('id')->on('semesters');
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->foreign('vendor_id', 'vendor_fk_8797633')->references('id')->on('vendors');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id', 'product_fk_8797634')->references('id')->on('book_variants');
            $table->unsignedBigInteger('plate_id')->nullable();
            $table->foreign('plate_id', 'plate_fk_8797635')->references('id')->on('materials');
            $table->unsignedBigInteger('chemical_id')->nullable();
            $table->foreign('chemical_id', 'chemical_fk_8797637')->references('id')->on('materials');
        });
    }
}
