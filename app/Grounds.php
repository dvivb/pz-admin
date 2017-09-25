<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Grounds extends Model
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
