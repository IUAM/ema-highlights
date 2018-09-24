<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->nullable()->index();
            $table->tinyInteger('position')->nullable()->index();
            $table->integer('total')->nullable();

            $table->text('title')->nullable();
            $table->text('title_medium')->nullable();
            $table->text('title_short')->nullable();
            $table->text('title_abbr')->nullable();

            // TODO: Consolidate these?
            $table->integer('tn_image_id')->nullable();
            $table->integer('tn_entry_id')->nullable();

            $table->text('description')->nullable();
            $table->text('description_md')->nullable();

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
        Schema::dropIfExists('categories');
    }
}
