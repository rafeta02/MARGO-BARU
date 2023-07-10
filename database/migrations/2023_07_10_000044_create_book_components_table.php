<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookComponentsTable extends Migration
{
    public function up()
    {
        Schema::create('book_components', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table->string('type');
            $table->integer('stock');
            $table->decimal('price', 15, 2)->nullable();
            $table->decimal('cost', 15, 2)->nullable();
            $table->boolean('status')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
