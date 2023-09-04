<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyCompleted extends Model
{
    protected $table = 'survey_completion';

    protected $fillable = ['survey_completion_id', 'completed_on', 'notification_sent', 'notification_read', 'survey_id', 'respondent_id', 'created_user_id'];

    public function users(){
        return $this->hasMany('App\User');
    }

    public function respondents(){
        return $this->hasMany('App\Respondent');
    }

    public function survey(){
        return $this->hasOne('App\Survey');
    }
}
