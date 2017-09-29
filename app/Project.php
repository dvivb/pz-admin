<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'code',
        'total_household',
        'total_areas',
        'amount',
        'created_at',
        'updated_at',
        'col_household'
    ];


    // 导入标准
    public static $importStandard = [
        'name',
        'total_household',
        'total_areas',
        'amount',
    ];

    /**
     * 获取导入/导出标题
     * @param $wic
     * @return string
     */
    public static function translationTitle($wic)
    {
        if ($wic === 'import_tpl') {
            return '项目管理导入模板';
        }
        elseif ($wic === 'export') {
            return '项目管理';
        }
    }

    /**
     * 获取导出的表头
     */
    public static function getSheetHeader($type='')
    {
        if ($type === 'export') {
            return [
                'id' => 'ID',
                'name' => '名称',
                'total_household' => '总户数',
                'total_areas' => '总面积',
                'amount' => '总金额',
                'created_at' => '创建时间',
                'updated_at' => '更新时间',
            ];
        }
        elseif ($type === 'import_tpl') {
            return [
                'name' => '名称',
                'total_household' => '总户数',
                'total_areas' => '总面积',
                'amount' => '总金额',
            ];
        }
    }
}
