<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class LandLevy extends Model
{
    protected $with = ['project'];

    protected $appends = [
        'project_name',     // 项目名称
    ];

    protected $visible = [];

    protected $fillable = [
        'period_id',
        'project_id',
        'id_number',
        'villages',
        'household_name',
        'gender',
        'contact',
        'address',
        'deposit_bank',
        'deposit_account',
        'provided_at',
        'remark',
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
     * 房屋征收字典表
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function landLevyDicSnap($subject='')
    {
        return
            $this
                ->hasMany(LandLevyDicSnap::class)
                ->when($subject, function ($query) use ($subject) {
                    $query->where('subject', $subject);
                });
    }

    // 获取 [项目名称] 属性
    public function getProjectNameAttribute()
    {
        if ($this->project) {
            return $this->project->name;
        }
    }

    /**
     * 获取 [总面积] 属性
     * @return integer
     */
    public function getTotalAreasAttribute()
    {
        if ($this->landLevyDicSnap) {
            return
                $this->landLevyDicSnap
                    ->whereIn('subject', ['land_status', 'young_crop'])
                    ->sum('numbers');
        }
    }

    /**
     * 获取 [总计] 属性
     * @return integer
     */
    public function getAmountAttribute()
    {
        if ($this->landLevyDicSnap) {
            return $this->landLevyDicSnap->sum('subtotal');
        }
    }
    
    /**
     * 汇总
     * @return bool
     */
    public function summarizing()
    {
        if ($this->landLevyDicSnap) {
            $this->setAttribute('total_areas', $this->total_areas);
            $this->setAttribute('amount', $this->amount);
            return $this->save();
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
            return '土地征收导入模板';
        }
        elseif ($wic === 'export') {
            return '土地征收';
        }
    }

    /**
     * 导入标准
     * @return array
     */
    public static function getImportStandard()
    {
        $importStandard = [
            'period_id',
            'project_id',
            'id_number',
            'villages',
            'household_name',
            'gender',
            'contact',
            'address',
            'land_status' => ['id', 'numbers'],
            'young_crop' => ['id', 'numbers'],
            'attach' => ['id', 'numbers'],
            'deposit_bank',
            'deposit_account',
            'provided_at',
            'remark',
        ];

        $_standard_mi = 0;
        return
            collect($importStandard)
                ->mapWithKeys(function ($standard_sg, $standard_sg_k) use (&$_standard_mi) {
                    if (is_string($standard_sg)) {
                        $pack_data = [$_standard_mi => $standard_sg];
                        $_standard_mi ++;
                    }
                    elseif (is_array($standard_sg)) {
                        $_standard_mi_tem = $_standard_mi;
                        $standard_sg_2 = collect($standard_sg)->mapWithKeys(function ($standard_sg_2) use (&$_standard_mi) {
                            $tem_data = [$_standard_mi => $standard_sg_2];
                            $_standard_mi++;

                            return $tem_data;
                        })->all();
                        $pack_data = [$_standard_mi_tem => [$standard_sg_k => $standard_sg_2]];
                    }

                    return $pack_data;
                })
                ->all();
    }

    /**
     * 导入数据
     * @param $pack_data
     * @return bool
     */
    public static function import($pack_data)
    {
        $pack_data->each(function ($value) {
            $land_levy = self::create($value->all());

            $land_levy_dic_snaps = collect();
            $value->each(function ($column_data) use ($land_levy, &$land_levy_dic_snaps) {
                if (!is_array($column_data)) {
                    return ;
                }

                collect($column_data)->map(function ($data) use ($land_levy, &$land_levy_dic_snaps) {
                    $dictionary = Dictionary::find($data['id']);
                    $land_levy_dic_snaps->push(
                        new LandLevyDicSnap([
                            'land_levy_id' => $land_levy->id,
                            'dictionary_parent_id' => $dictionary->parent_id,
                            'dictionary_id' => $dictionary->id,
                            'subject' => $dictionary->subject,
                            'name' => $dictionary->name,
                            'unit' => $dictionary->unit,
                            'price' => $dictionary->price,
                            'numbers' => $data['numbers'],
                            'remarks' => $dictionary->remarks,
                        ])
                    );
                });

            });

            $land_levy->landLevyDicSnap()->saveMany($land_levy_dic_snaps->all());

            $land_levy->summarizing();
        });

        return true;
    }

    /**
     * 获取导出的表头
     * @param string $type
     * @return array
     */
    public static function getSheetHeader($type = '')
    {
        if ($type === 'export') {
            return [
                'id'=> "ID",
                'period_id' => '批次号',
                'project_name' => '项目名称',
                'id_number' => '身份证号',
                'villages' => '乡镇',
                'household_name' => '姓名',
                'gender' => '性别',
                'contact' => '联系电话',
                'address' => '家庭住址',
                'land_status' => [
                    'name' => '土地类别',
                    'signed' => 'land_levy_dic_snap',
                    '_child' => [
                        'full_name' => '种类',
                        'unit' => '单位（亩）',
                        'price' => '单价',
                        'numbers' => '数量',
                        'subtotal' => '小计',
                    ]
                ],
                'young_crop' => [
                    'name' => '青苗',
                    'signed' => 'land_levy_dic_snap',
                    '_child' => [
                        'full_name' => '种类',
                        'unit' => '单位（亩）',
                        'price' => '单价',
                        'numbers' => '数量',
                        'subtotal' => '小计',
                    ],
                ],
                'attach' => [
                    'name' => '地上附着物',
                    'signed' => 'land_levy_dic_snap',
                    '_child' => [
                        'full_name' => '种类',
                        'unit' => '单位',
                        'price' => '单价',
                        'numbers' => '数量',
                        'subtotal' => '小计',
                    ],
                ],
                'total_areas' => '总面积（亩）',
                'amount' => '总计',
                'deposit_bank' => '开户银行',
                'deposit_account' => '开户账号',
                'provided_at' => '发放时间',
                'remark' => '备注',
            ];
        }
    }

    /**
     * 获取工作表的列宽
     * @return array
     */
    public static function getSheetWidth()
    {
        return
            [
                'id'=> 8,
                'period_id' => 8,
                'project_name' => 36,
                'id_number' => 25,
                'villages' => 25,
                'household_name' => 13,
                'gender' => 8,
                'contact' => 22,
                'address' => 36,
                'land_status' => [
                    'full_name' => 36,
                    'unit' => 8,
                    'price' => 12,
                    'numbers' => 8,
                    'subtotal' => 12,
                ],
                'young_crop' => [
                    '__reuse' => 'land_status'
                ],
                'attach' => [
                    '__reuse' => 'land_status'
                ],
                'total_areas' => 20,
                'amount' => 20,
                'deposit_bank' => 20,
                'deposit_account' => 20,
                'provided_at' => 20,
                'remark' => 30,
            ];
    }
}
