<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments("id");
            $table->string("name", 100);
            $table->string("description", 4000)->nullable();
            $table->unique("name");
            $table->timestamps();
        });


        Schema::table('profiles', function (Blueprint $table) {
            $table->integer("created_by")->unsigned();

            //Foranea para saber quien creo el perfil
            $table->foreign('created_by')->references('id')->on('users');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
        });

        Schema::drop('profiles');
    }
}
