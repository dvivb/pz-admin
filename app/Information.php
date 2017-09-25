<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
//    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'body',
    ];

}
