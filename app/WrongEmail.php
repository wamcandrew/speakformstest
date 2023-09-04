<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WrongEmail extends Model
{
    protected $table = 'wrong_email';
    
    protected $fillable = ['email', 'problem_type', 'repeated_attempts'];
}
