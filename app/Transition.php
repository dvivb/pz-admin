<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transition extends Model
{
    protected $fillable = [
        'name',
        'id_number',
        'villages',
        'address',
        'contact',
        'signed_at',
        'living_area',
        'living_price',
        'business_area',
        'business_price',
        'amount',
        'started_at',
        'ended_at',
        'signed',
        'outed_at',
        'remarks',
    ];

    // 导入标准
    public static $importStandard = [
        'name',
        'id_number',
        'villages',
        'address',
        'contact',
        'signed_at',
        'living_area',
        'living_price',
        'business_area',
        'business_price',
        'amount',
        'started_at',
        'ended_at',
        'signed',
        'outed_at',
        'remarks',
    ];

    /**
     * 获取导入/导出标题
     * @param $wic
     * @return string
     */
    public static function translationTitle($wic)
    {
        if ($wic === 'import_tpl') {
            return '过渡费发放导入模板';
        }
        elseif ($wic === 'export') {
            return '过渡费发放';
        }
    }

    /**
     * 获取导出的表头
     */
    public static function getSheetHeader($type = '')
    {
        if ($type === 'export') {
            return [
                'id' => 'ID',
                'name' => '姓名',
                'id_number' => '身份证号码',
                'villages' => '乡镇',
                'address' => '住址',
                'contact' => '联系电话',
                'signed_at' => '签约时间',
                'living_area' => '居住面积',
                'living_price' => '居住过渡费',
                'business_area' => '商业面积',
                'business_price' => '商业过渡费',
                'amount' => '总过渡费',
                'started_at' => '起始时间',
                'ended_at' => '起止时间',
                'signed' => '签名',
                'outed_at' => '发放时间',
                'remarks' => '备注',
            ];
        }
        elseif ($type === 'import_tpl') {
            return [
                'name' => '姓名',
                'id_number' => '身份证号码',
                'villages' => '乡镇',
                'address' => '住址',
                'contact' => '联系电话',
                'signed_at' => '签约时间',
                'living_area' => '居住面积',
                'living_price' => '居住过渡费',
                'business_area' => '商业面积',
                'business_price' => '商业过渡费',
                'amount' => '总过渡费',
                'started_at' => '起始时间',
                'ended_at' => '起止时间',
                'signed' => '签名',
                'outed_at' => '发放时间',
                'remarks' => '备注',
            ];
        }
    }
}
