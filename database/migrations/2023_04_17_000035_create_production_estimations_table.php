<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionEstimationsTable extends Migration
{
    public function up()
    {
        Schema::create('production_estimations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
            $table->integer('estimasi')->nullable();
            $table->integer('isi')->nullable();
            $table->integer('cover')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
