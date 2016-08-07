<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppErrorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("app_errors", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("user_id", false, true)->nullable();
            $table->string('php_class');
            $table->binary('work_data')->nullable();
            $table->binary('message');
            $table->timestamps();
        });

        Schema::table("app_errors", function (Blueprint $table) {
            $table->foreign("user_id")->references("id")->on("users");
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("app_errors");
    }
}
