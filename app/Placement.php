<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Placement extends Model
{
    protected $with = 'project';

    protected $appends = [
        'project_name', // 项目名称
    ];

    protected $casts = [
        'type' => 'integer', // 类型
    ];

    protected $fillable = [
        'project_id',
        'address',
        'type',
        'total_areas',
        'remarks',
    ];

    // 导入标准
    public static $importStandard = [
        'project_id',
        'address',
        'type',
        'total_areas',
        'remarks'
    ];

    /**
     * 项目对象
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    // 获取 [项目名称] 属性
    public function getProjectNameAttribute()
    {
        if ($this->project) {
            return $this->project->name;
        }
    }

    /**
     * 获取导入/导出标题
     * @param $wic
     * @return string
     */
    public static function translationTitle($wic)
    {
        if ($wic === 'import_tpl') {
            return '安置信息导入模板';
        }
        elseif ($wic === 'export') {
            return '安置信息';
        }
    }

    /**
     * 获取导出的表头
     * @return array
     */
    public static function getSheetHeader($type='')
    {
        if ($type === 'export') {
            return [
                'id' => 'ID',
                'project_name' => '项目名称',
                'address' => '地址',
                'type' => '类型',
                'total_areas' => '总面积',
                'remarks' => '备注',
                'created_at' => '创建时间',
                'updated_at' => '更新时间',
            ];
        }
        elseif ($type === 'import_tpl')
        {
            return [
                'project_id' => '项目ID',
                'address' => '地址',
                'type' => '类型（1.住房 2.商业）',
                'total_areas' => '总面积',
                'remarks' => '备注',
            ];
        }
    }
}
