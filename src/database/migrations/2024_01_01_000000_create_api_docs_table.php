<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiDocsTable extends Migration
{
    public function up()
    {
        Schema::create('api_docs', function (Blueprint $table) {
            $table->id();
            $table->text('route')->nullable();
            $table->string('method', 255)->nullable();
            $table->longText('header')->nullable();
            $table->longText('body')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_docs');
    }
}