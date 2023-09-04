<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $table = 'survey';
    
    protected $fillable = ['survey_id', 'survey_name', 'survey_creator', 'user_id'];

    public function questions() {
        return $this->hasMany('App\Question');  
    }

    public function users() {
        return $this->hasMany('App\User');
    }
}
