<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLangTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('langs', function (Blueprint $table) {
            $table->increments("id");
            $table->string('code', 4)->unique();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('label_langs', function (Blueprint $table) {
            $table->increments("id");
            $table->string("label");
            $table->string("def");
            $table->integer("lang_id", false, true);
            $table->timestamps();
        });

        Schema::table('label_langs', function (Blueprint $table) {
            $table->foreign("lang_id")->references("id")->on("langs");
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('label_langs');
        Schema::drop('langs');
    }
}
