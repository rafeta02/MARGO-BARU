<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinishingItemsTable extends Migration
{
    public function up()
    {
        Schema::create('finishing_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('estimasi');
            $table->integer('quantity');
            $table->decimal('cost', 15, 2)->nullable();
            $table->boolean('done')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
