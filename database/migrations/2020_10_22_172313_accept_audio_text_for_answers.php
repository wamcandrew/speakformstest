<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AcceptAudioTextForAnswers extends Migration
{
    /**
     * Change answers table to accept both audio and text
     *
     * @return void
     */
    public function up()
    {
        Schema::table('answer', function(Blueprint $table) {
            $table->dropColumn('is_audio');
            $table->text('answer_url')->after('answer_content')->nullable();
            $table->text('answer_content')->nullable();
        });
    }

    /**
     * Reverse the change and accept either audio/text
     *
     * @return void
     */
    public function down()
    {
        Schema::table('answer', function(Blueprint $table) {
            $table->boolean('is_audio');
            $table->dropColumn('answer_url');
        });
    }
}
