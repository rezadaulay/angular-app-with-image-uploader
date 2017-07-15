<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsImage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['news_id', 'cover', 'file_name', 'caption'];
}
