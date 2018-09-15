<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->increments('id');

            // TODO: Dedupe w/ objects?
            $table->text('accession')->nullable();
            $table->bigInteger('accession_sort')->nullable();

            $table->text('title')->nullable();
            $table->text('title_sort')->nullable();

            $table->text('tombstone')->nullable();
            $table->text('tombstone_md')->nullable();

            $table->text('description')->nullable();
            $table->text('description_md')->nullable();

            $table->boolean('is_copyrighted')->default(false);

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
        Schema::dropIfExists('entries');
    }
}
