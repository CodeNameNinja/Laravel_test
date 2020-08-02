<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Post extends Model
{

    protected $fillable =[
        'title',
        'content'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
