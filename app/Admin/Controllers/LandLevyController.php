<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\CustomeCreation;
use App\Admin\Extensions\Importer;
use App\Admin\Extensions\CustomExporter;
use App\Admin\Extensions\PeriodCreation;
use App\Dictionary;
use App\landLevy;

use App\Period;
use App\Project;
use Encore\Admin\Grid;
use Encore\Admin\Form;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\Input;

class LandLevyController extends Controller
{
    use ModelForm;

    const TITLE = '土地征收';

    protected $grid;            // 网格对象
    protected $description='';  // 描述

    /**
     * 首页启动器
     */
    protected function indexBoot()
    {
        $step = Input::get('step', 1);
        switch ($step) {
            case 1:
                // 获取网格对象
                $this->grid = $this->projectGrid();
                break;
            case 2:
                $project_id = Input::get('project_id');

                // 获取网格对象
                $this->grid = $this->periodGrid();

                // 设置描述
                $project_name = Project::where('id', $project_id)->value('name');
                $this->description = "<a href='land_levies' style='padding: 0;font-size: 15px;font-weight: 400;' class='btn btn-link'>$project_name</a>";
                break;
            case 3:
                $project_id = Input::get('project_id');
                $period_id = Input::get('period_id');

                // 获取网格对象
                $this->grid = $this->infoGrid();
                // 设置描述
                $project_name = Project::where('id', $project_id)->value('name');
                $period_name = Period::find($period_id)->name;
                $this->description = "<a href='land_levies' style='padding: 0;font-size: 15px;font-weight: 400;' class='btn btn-link'>$project_name</a> - <a href='land_levies?step=2&project_id=$project_id' style='padding: 0;font-size: 15px;font-weight: 400;' class='btn btn-link'>$period_name</a>";
                break;
        }
    }

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        $this->indexBoot();
        return Admin::content(function (Content $content) {

            $content->header(self::TITLE);
            $content->description($this->description);
            $content->body($this->grid);
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header(self::TITLE);
            $content->description('');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header(self::TITLE);
            $content->description();

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */


    /**
     * The grid of project
     * @return Grid
     */
    private function projectGrid()
    {
        return Admin::grid(Project::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->name('项目名称')->display(function ($name) {
                return "<a href='?step=2&project_id={$this->id}'>$name</a>";
            });

            $grid->land_landhold_nums('总户数');
            $grid->land_areas('总面积');
            $grid->land_amount('总金额');
            $grid->period('土地征补拨款期数');

            $grid->disableCreation();
            $grid->disableExport();
            $grid->disableRowSelector();
            $grid->disableActions();
        });
    }

    /**
     * The grid of period
     * @return Grid
     */
    private function periodGrid()
    {
        return Admin::grid(Period::class, function (Grid $grid) {
            $project_id = Input::get('project_id');
            $grid->model()->where('project_id', $project_id);

            $grid->id('ID')->sortable();
            $grid->period('期数')->display(function ($period) {
                return "<a href='?step=3&project_id={$this->project_id}&period_id={$this->id}'>{$this->name}</a>";
            });
            $grid->total_nums('总户数');
            $grid->total_areas('总面积');
            $grid->total_amount('总金额');


            $grid->tools(function ($tools) use ($grid, $project_id) {
                $period = $grid->model()->eloquent()->latest()->first();
                $tools->append(new PeriodCreation('periods', [
                    'project_id' => $project_id,
                    'period' => $period && is_object($period) ? $period->period + 1 : 1
                ]));

                // $tools->append(new delete('period/' . $period->id));
            });

            $grid->actions(function ($actions) {
                $actions->disableEdit();

                $actions->setResource('periods');
            });

            $grid->disableCreation();
            $grid->disableExport();
            $grid->disableRowSelector();
        });
    }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    private function infoGrid()
    {
        return Admin::grid(landLevy::class, function (Grid $grid) {
            $project_id = Input::get('project_id');
            $period_id = Input::get('period_id');

            $grid->model()
                ->where('project_id', $project_id)
                ->where('period_id', $period_id);

            $grid->id('ID')->sortable();

            $grid->id_number('身份证号');
            $grid->villages('乡镇');
            $grid->landhold_name('姓名');
            $grid->gender('性别')->display(function ($gendered) {
                return $gendered === 'm' ? '男性' : '女性';
            })->sortable();
            $grid->contact('联系电话');
            $grid->address('家庭住址');

            $grid->created_at('创建时间')->sortable();
            $grid->updated_at('更新时间')->sortable();

            $grid->filter(function ($filter) {
                $filter->useModal();
                $filter->like('landhold_name', '姓名');

                $filter
                    ->is('project_id', '项目')
                    ->select(Project::all()->pluck('name', 'id'));
            });

            // 导出数据
            $custom_exporter = new CustomExporter($grid, landLevy::class, 'one_to_many');
            // 整合导出的数据
            $custom_exporter->making(function (CustomExporter $exporter) {
                $exporter->grid()->model()->with(['landLevyDicSnap']);

                $exporter->grid()->model()->collection(function ($model) {
                    $model->each(function ($item) {
                        $item->landLevyDicSnap->makeVisible(['full_name', 'subtotal']);
                    });
                    $model->makeVisible(['project_name', 'period_name']);

                    $model->groupBy('subject');

                    return $model;
                });

                return $exporter->getData();

            });
            $grid->exporter($custom_exporter);

            $grid->tools(function ($tools) use ($grid, $project_id, $period_id) {
                $tools->append(new Importer(landLevy::class));
                $tools->append(new CustomeCreation("land_levies/create?step=3&project_id=$project_id&period_id=$period_id"));
            });

            $grid->actions(function ($actions) use ($project_id, $period_id) {
                $actions->setEditActionResource("/admin/land_levies/{$actions->getKey()}/edit?step=3&project_id=$project_id&period_id=$period_id");
                // $actions->disableEdit();

                // $actions->append(new CustomDelete($actions->getKey(), 'periods'));
            });
            $grid->disableCreation();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(landLevy::class, function (Form $form) {
            $project_id = Input::get('project_id');
            $period_id = Input::get('period_id');

            $form->tab('被征户主基本信息', function ($form) use ($project_id, $period_id) {
                $form->display('id', 'ID');

                $form->display('project_name', '项目')->default(Project::where('id', $project_id)->value('name'));
                $form->display('period_name', '期数')->default($period_id ? Period::find($period_id)->name  : '');

                $form->hidden('project_id')->value($project_id);
                $form->hidden('period_id')->value($period_id);

                $form->text('id_number', '身份证号')->rules('required');
                $form->text('villages', '乡镇')->rules('required');
                $form->text('landhold_name', '姓名')->rules('required');
                $form->radio('gender', '性别')->options(['m' => '男性', 'f'=> '女性'])->default('m')->rules('required');
                $form->text('address', '家庭住址')->rules('required');
                $form->text('contact', '联系电话')->rules('required');

                $form->text('deposit_bank', '开户银行');
                $form->text('deposit_account', '开户账号');
                $form->datetime('provided_at', '发放时间');
                $form->text('remark', '备注');

                $form->display('created_at', '创建时间');
                $form->display('updated_at', '更新时间');
            });

            $form->tab('土地类别', function ($form) {
                $subject = 'land_status';

                $form->customizing(function ($element) use ($subject) {
                    $element->setRelationName('landLevyDicSnap');
                    $element->groupBy(['subject' => $subject]);
                });

                $form->hasMany('landLevyDicSnap_batch_'.$subject, '字典表', function (Form\NestedForm $form) use ($subject) {
                    $form->hidden('subject')->value($subject); // 主题
                    // first level
                    $form
                        ->select('dictionary_parent_id', '父级分类')
                        ->options(function ($id) use ($subject) {
                            $dictionary = Dictionary::find($id);

                            if ($dictionary) {
                                return $dictionary->getWithSameClasses($subject);
                            }

                        })
                        ->ajax('/admin/api/dictionaries/' . $subject)
                        ->load('dictionary_id', '/admin/api/dictionaries/' . $subject . '/isdeep');

                    // third level
                    $form
                        ->select('dictionary_id', '子级分类')
                        ->options(function ($id) use ($subject) {
                            $dictionary = Dictionary::find($id);

                            if ($dictionary) {
                                return $dictionary->getWithSameClasses($subject);
                            }
                        });

                    $form->number('numbers', '面积（m²）');
                });
            });

            $form->tab('青苗', function ($form) {
                $subject = 'young_crop';

                $form->customizing(function ($element) use ($subject) {
                    $element->setRelationName('landLevyDicSnap');
                    $element->groupBy(['subject' => $subject]);
                });

                $form->hasMany('landLevyDicSnap_batch_'.$subject, '字典表', function (Form\NestedForm $form) use ($subject) {
                    $form->hidden('subject')->value($subject); // 主题
                    // first level
                    $form
                        ->select('dictionary_parent_id', '父级分类')
                        ->options(function ($id) use ($subject) {
                            $dictionary = Dictionary::find($id);

                            if ($dictionary) {
                                return $dictionary->getWithSameClasses($subject);
                            }

                        })
                        ->ajax('/admin/api/dictionaries/' . $subject)
                        ->load('dictionary_id', '/admin/api/dictionaries/' . $subject . '/isdeep');

                    // third level
                    $form
                        ->select('dictionary_id', '子级分类')
                        ->options(function ($id) use ($subject) {
                            $dictionary = Dictionary::find($id);

                            if ($dictionary) {
                                return $dictionary->getWithSameClasses($subject);
                            }
                        });

                    $form->number('numbers', '数量');
                });
            });

            $form->tab('地上附着物', function ($form) {
                $subject = 'attach';

                $form->customizing(function ($element) use ($subject) {
                    $element->setRelationName('landLevyDicSnap');
                    $element->groupBy(['subject' => $subject]);
                });

                $form->hasMany('landLevyDicSnap_batch_'.$subject, '字典表', function (Form\NestedForm $form) use ($subject) {
                    $form->hidden('subject')->value($subject); // 主题
                    // first level
                    $form
                        ->select('dictionary_parent_id', '父级分类')
                        ->options(function ($id) use ($subject) {
                            $dictionary = Dictionary::find($id);

                            if ($dictionary) {
                                return $dictionary->getWithSameClasses($subject);
                            }

                        })
                        ->ajax('/admin/api/dictionaries/' . $subject)
                        ->load('dictionary_id', '/admin/api/dictionaries/' . $subject . '/isdeep');

                    // third level
                    $form
                        ->select('dictionary_id', '子级分类')
                        ->options(function ($id) use ($subject) {
                            $dictionary = Dictionary::find($id);

                            if ($dictionary) {
                                return $dictionary->getWithSameClasses($subject);
                            }
                        });

                    $form->number('numbers', '面积（m²）');
                });
            });

            $form->relationMaking(function ($attributes) {
                if (!$attributes) return [];

                // 字典表对象
                $dictionary = Dictionary::find($attributes['dictionary_id']);
                // 字典表构建属性
                $dictionary->setVisible(['subject','name','unit','price','remarks']);

                return $dictionary->toArray();
            });

            $form->saved(function ($form) {
                $model = $form->model();
                $model->summarizing();
            });

        });
    }
}
