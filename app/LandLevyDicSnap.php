<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LandLevyDicSnap extends Model
{
    protected $appends = [
        'full_name', // 全名称
        'subtotal',  // 小计
    ];

    protected $fillable = [
        'land_levy_id',         // 土地征收表ID
        'dictionary_parent_id', // 字典表ID
        'dictionary_id',        // 字典表ID
        'subject',              // 主题名称
        'numbers',              // 数量
        'name',                 // 名称
        'unit',                 // 单位
        'price',                // 补偿标准
        'remarks',              // 备注
    ];

    /**
     * 房屋征收对象
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function landLevy()
    {
        return $this->belongsTo(landLevy::class);
    }

    /**
     * 字典表对象
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dictionary($column='dictionary_id')
    {
        return $this->belongsTo(Dictionary::class, $column);
    }

    /**
     * 限制查找只包括当前主题的字典数据
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfSubject($query, $subject)
    {
        return $query->where('subject', $subject);
    }

    /**
     * 获取 [全名称] 属性
     * @return string
     */
    public function getFullNameAttribute()
    {
        $parent_name = '';
        $cur_name = '';
        if ($this->dictionary) {
            $cur_name = $this->dictionary->name;
        }

        if ($this->dictionary('parent_id')) {
            $parent_name = $this->dictionary('dictionary_parent_id')->value('name');
        }

        return $parent_name . ' - ' . $cur_name;
    }

    /**
     * 获取 [小计] 属性
     * @return string
     */
    public function getSubtotalAttribute()
    {
        return $this->attributes['price'] * $this->attributes['numbers'];
    }
}
