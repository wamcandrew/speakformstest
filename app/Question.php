<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'survey_questions';

    protected $fillable = ['question_id', 'survey_id', 'question_no', 'question_content'];

    public function survey() {
        return $this->belongsTo('App\Survey');
    }
}
