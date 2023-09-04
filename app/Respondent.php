<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Respondent extends Model
{
    protected $table = 'respondent';

    protected $fillable = ['respondent_id', 'survey_id', 'respondent_email', 'notification_sent'];

    public function survey_completed(){
        return $this->hasOne('App/SurveyCompleted');
    }
}
