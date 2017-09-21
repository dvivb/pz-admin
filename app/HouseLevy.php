<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class HouseLevy extends Model
{
    protected $with = [];

    protected $appends = [
        'project_name',     // 项目名称
        'period_name',      // 期数名称
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
        'house_decoration',
        'receive_extra',
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
     * 期数对象
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function period()
    {
        return $this->belongsTo('App\Period');
    }

    /**
     * 房屋征收字典表
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function houseLevyDicSnap($subject='')
    {
        return
            $this
                ->hasMany(HouseLevyDicSnap::class)
                ->when($subject, function ($query) use ($subject) {
                    $query->where('subject', $subject);
                });
    }

    /**
     * 获取 [项目名称] 属性
     * @return string
     */
    public function getProjectNameAttribute()
    {
        if ($this->project) {
            return $this->project->name;
        }
    }

    /**
     * 获取 [期数名称] 属性
     * @return string
     */
    public function getPeriodNameAttribute()
    {
        if ($this->period) {
            return $this->period->name;
        }
    }

    /**
     * 获取 [总面积] 属性
     * @return integer
     */
    public function getTotalAreasAttribute()
    {
        if ($this->houseLevyDicSnap) {
            return
                $this->houseLevyDicSnap
                    ->whereIn('subject', ['house_structure', 'annexe_structure'])
                    ->sum('numbers');
        }
    }

    /**
     * 获取 [总计] 属性
     * @return integer
     */
    public function getAmountAttribute()
    {
        $amount = 0;
        if ($this->houseLevyDicSnap) {
            $amount += $this->houseLevyDicSnap->sum('subtotal');
        }

        $amount += $this->attributes['house_decoration'];
        $amount += $this->attributes['receive_extra'];

        return $amount;
    }

    /**
     * 回滚汇总
     * @return bool
     */
    public function unsummarizing()
    {
        if ($this->houseLevyDicSnap) {
            DB::transaction(function () {
                // 期数 汇总的挂载更新
                if ($this->period) {
                    $this->period->total_nums -= 1;
                    $this->period->total_areas -= $this->total_areas;
                    $this->period->total_amount -= $this->amount;
                    $this->period->save();
                }

                // 项目 汇总的挂载更新
                if ($this->project) {
                    $this->project->house_household_nums -= 1;
                    $this->project->house_areas -= $this->total_areas;
                    $this->project->house_amount -= $this->amount;
                    $this->project->save();
                }
            });

            return true;
        }
    }

    /**
     * 汇总
     * @return bool
     */
    public function summarizing()
    {
        if ($this->houseLevyDicSnap) {
            DB::transaction(function () {
                $this->setAttribute('total_areas', $this->total_areas);
                $this->setAttribute('amount', $this->amount);
                $this->save();

                // 期数 汇总的挂载更新
                if ($this->period) {
                    $this->period->total_nums += 1;
                    $this->period->total_areas += $this->total_areas;
                    $this->period->total_amount += $this->amount;
                    $this->period->save();
                }

                // 项目 汇总的挂载更新
                if ($this->project) {
                    $this->project->house_household_nums += 1;
                    $this->project->house_areas += $this->total_areas;
                    $this->project->house_amount += $this->amount;
                    $this->project->save();
                }
            });

            return true;
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
            return '房屋征收导入模板';
        }
        elseif ($wic === 'export') {
            return '房屋征收';
        }
    }

    /**
     * 导入标准
     * @return array
     */
    public static function getImportStandard()
    {
        $importStandard = [
            'project_id',
            'period_id',
            'id_number',
            'villages',
            'household_name',
            'gender',
            'contact',
            'address',
            'house_structure' => ['id', 'numbers'],
            'annexe_structure' => ['id', 'numbers'],
            'attach' => ['id', 'numbers'],
            'structure' => ['id', 'numbers'],
            'equipment' => ['id', 'numbers'],
            'house_decoration',
            'receive_extra',
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
            $house_levy = self::create($value->all());

            $house_levy_dic_snaps = collect();
            $value->each(function ($column_data, $column) use ($house_levy, &$house_levy_dic_snaps) {
                if (!is_array($column_data)) {
                    return ;
                }

                collect($column_data)->map(function ($data) use ($house_levy, &$house_levy_dic_snaps) {
                    $dictionary = Dictionary::find($data['id']);
                    $house_levy_dic_snaps->push(
                        new HouseLevyDicSnap([
                            'house_levy_id' => $house_levy->id,
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

            $house_levy->houseLevyDicSnap()->saveMany($house_levy_dic_snaps->all());

            $house_levy->summarizing();
        });

        return true;
    }

    /**
     * 获取 导出/导入模板 的表头
     * @param string $type
     * @return array
     */
    public static function getSheetHeader($type = '')
    {
        if ($type === 'export') {
            return [
                'id'=> "ID",
                'project_name' => '项目名称',
                'period_name' => '期数',
                'id_number' => '身份证号',
                'villages' => '乡镇',
                'household_name' => '姓名',
                'gender' => '性别',
                'contact' => '联系电话',
                'address' => '家庭住址',
                'house_structure' => [
                    'name' => '房屋结构',
                    'signed' => 'house_levy_dic_snap',
                    '_child' => [
                        'full_name' => '种类',
                        'unit' => '单位（m²）',
                        'price' => '单价',
                        'numbers' => '数量',
                        'subtotal' => '小计',
                    ]
                ],
                'annexe_structure' => [
                    'name' => '附房结构',
                    'signed' => 'house_levy_dic_snap',
                    '_child' => [
                        'full_name' => '种类',
                        'unit' => '单位（m²）',
                        'price' => '单价',
                        'numbers' => '数量',
                        'subtotal' => '小计',
                    ],
                ],
                'attach' => [
                    'name' => '地上附着物',
                    'signed' => 'house_levy_dic_snap',
                    '_child' => [
                        'full_name' => '种类',
                        'unit' => '单位',
                        'price' => '单价',
                        'numbers' => '数量',
                        'subtotal' => '小计',
                    ],
                ],
                'structure' => [
                    'name' => '构筑物',
                    'signed' => 'house_levy_dic_snap',
                    '_child' => [
                        'full_name' => '种类',
                        'unit' => '单位',
                        'price' => '单价',
                        'numbers' => '数量',
                        'subtotal' => '小计',
                    ],
                ],
                'equipment' => [
                    'name' => '配套设施',
                    'signed' => 'house_levy_dic_snap',
                    '_child' => [
                        'full_name' => '种类',
                        'unit' => '单位',
                        'price' => '单价',
                        'numbers' => '数量',
                        'subtotal' => '小计',
                    ],
                ],
                'house_decoration' => '房屋装修金额',
                'receive_extra' => '搬迁奖励金额',
                'total_areas' => '总面积（m²）',
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
            'project_name' => 36,
            'period_name' => 8,
            'id_number' => 25,
            'villages' => 25,
            'household_name' => 13,
            'gender' => 8,
            'contact' => 22,
            'address' => 36,
            'house_structure' => [
                'full_name' => 36,
                'unit' => 8,
                'price' => 12,
                'numbers' => 8,
                'subtotal' => 12,
            ],
            'annexe_structure' => [
                '__reuse' => 'house_structure'
            ],
            'attach' => [
                '__reuse' => 'house_structure'
            ],
            'structure' => [
                '__reuse' => 'house_structure'
            ],
            'equipment' => [
                '__reuse' => 'house_structure'
            ],
            'house_decoration' => 20,
            'receive_extra' => 20,
            'total_areas' => 20,
            'amount' => 20,
            'deposit_bank' => 20,
            'deposit_account' => 20,
            'provided_at' => 20,
            'remark' => 30,
        ];
    }
}
