<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('survey_questions', function (Blueprint $table) {
            $table->string('survey_id');

            $table->foreign('survey_id')->references('survey_id')->on('survey')->onDelete('cascade');
        });

        Schema::table('respondent', function (Blueprint $table) {
            $table->string('survey_id');

            $table->foreign('survey_id')->references('survey_id')->on('survey')->onDelete('cascade');
        });

        Schema::table('survey_completion', function (Blueprint $table) {
            $table->string('survey_id');
            $table->string('respondent_id');

            $table->foreign('survey_id')->references('survey_id')->on('survey')->onDelete('cascade');
            $table->foreign('respondent_id')->references('respondent_id')->on('respondent')->onDelete('cascade');
        });

        Schema::table('answer', function (Blueprint $table) {
            $table->string('survey_completion_id');
            $table->string('question_id');
            $table->string('respondent_id');

            $table->foreign('survey_completion_id')->references('survey_completion_id')->on('survey_completion')->onDelete('cascade');
            $table->foreign('question_id')->references('question_id')->on('survey_questions')->onDelete('cascade');
            $table->foreign('respondent_id')->references('respondent_id')->on('respondent')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('survey_questions', function (Blueprint $table) {            
            $table->dropForeign(['survey_id']);
            $table->dropColumn('survey_id');
        });

        Schema::table('respondent', function (Blueprint $table) {
            $table->dropForeign(['survey_id']);
            $table->dropColumn('survey_id');
        });

        Schema::table('survey_completion', function (Blueprint $table) {            
            $table->dropForeign(['survey_id']);
            $table->dropForeign(['respondent_id']);

            $table->dropColumn('survey_id');
            $table->dropColumn('respondent_id');
        });

        Schema::table('answer', function (Blueprint $table) {            
            $table->dropColumn(['survey_completion_id']);
            $table->dropColumn(['question_id']);
            $table->dropColumn(['respondent_id']);

            $table->dropColumn('survey_completion_id');
            $table->dropColumn('question_id');
            $table->dropColumn('respondent_id');
        });
    }
}
