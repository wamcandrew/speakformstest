<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $table = 'answer';

    protected $fillable = ['answer_id', 'survey_completion_id', 'question_id', 'respondent_id', 'survey_id', 'is_audio', 'answer_content', 'answer_url'];
}
