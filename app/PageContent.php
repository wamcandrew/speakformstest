<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    protected $table = 'page_content';

    protected $fillable = ['id', 'body'];
}
