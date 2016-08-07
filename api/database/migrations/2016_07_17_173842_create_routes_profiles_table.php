<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutesProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('method', array('*', 'POST', 'DELETE', 'UPDATE', 'PUT', 'GET'));
            $table->integer('profile_id')->unsigned();
            $table->integer('route_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('route_profiles', function (Blueprint $table) {
            $table->foreign('profile_id')->references('id')->on('profiles');
            $table->foreign('route_id')->references('id')->on('routes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('route_profiles');
    }
}
