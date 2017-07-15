<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'content'
    ];
    
	public function images()
    {
        return $this->hasMany('App\NewsImage')->orderBy('cover','desc');
    }
}
