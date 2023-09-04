<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReadMetaForNotification extends Migration
{
    /**
     * Add read meta for Survey Completion table
     *
     * @return void
     */
    public function up()
    {
        Schema::table('survey_completion', function(Blueprint $table) {
            $table->boolean('notification_read')->after('notification_sent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('survey_completion', function(Blueprint $table) {
            $table->dropColumn('notification_read');
        });
    }
}
