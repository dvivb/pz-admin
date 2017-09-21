<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;


class Dictionary extends Model
{
    use ModelTree, AdminBuilder;

    protected $casts = [
        'parent_id' => 'integer',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setOrderColumn('sort');
        $this->setTitleColumn('name');
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

    public function getWithSameClasses($subject='')
    {
        return
            self
                ::ofSubject($subject)
                ->where('parent_id', $this->attributes['parent_id'])
                ->pluck('name', 'id');
    }
}
