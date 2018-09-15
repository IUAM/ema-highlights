<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');

            $table->text('filename')->nullable();

            $table->decimal('cx', 9, 8)->nullable();
            $table->decimal('cy', 9, 8)->nullable();
            $table->decimal('cw', 9, 8)->nullable();
            $table->decimal('ch', 9, 8)->nullable();

            $table->text('base64_tn')->nullable();
            $table->text('base64_sq')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
}
