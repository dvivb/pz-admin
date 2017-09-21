<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $appends = ['name'];

    protected $fillable = [
        'project_id',
        'period',
    ];

    /**
     * 项目对象
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    /**
     * 房屋征收对象
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function houseLevy()
    {
        return $this->hasMany('App\HouseLevy');
    }

    protected function getNameAttribute()
    {
        return '第' . numToWord($this->period) . '期';
    }
}
