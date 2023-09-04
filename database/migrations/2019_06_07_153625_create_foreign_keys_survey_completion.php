<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeysSurveyCompletion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('survey_completion', function (Blueprint $table) {
            $table->bigInteger('created_user_id')->unsigned();

            $table->foreign('created_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('survey_completion', function (Blueprint $table) {            
            $table->dropForeign(['created_user_id']);
            $table->dropColumn('created_user_id');
        });
    }
}
