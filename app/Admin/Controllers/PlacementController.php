<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\CustomExporter;
use App\Admin\Extensions\Importer;
use App\Admin\Extensions\ImportTmplExporter;
use App\Placement;

use App\Project;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class PlacementController extends Controller
{
    use ModelForm;

    const TITLE = '安置信息';

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header(self::TITLE);
            $content->description('');

            $content->body($this->grid());
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

            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Placement::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->project_name('项目名称');
            $grid->address('地址');
            $grid->type('类型')->display(function ($type) {
                if ($type === 1) return '住房';
                elseif ($type === 2) return '商业';
            });
            $grid->total_areas('总面积');
            $grid->remarks('备注');

            $grid->created_at('创建时间');
            $grid->updated_at('更新时间');

            $grid->exporter(new CustomExporter(null, Placement::class));

            $grid->tools(function ($tools) use ($grid) {
                $tools->append(new Importer(Placement::class));
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Placement::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->select('project_id', '项目')->options(function ($id) {
                $project = Project::find($id);

                if ($project) {
                    return [$project->id => $project->name];
                }
            })->ajax('/admin/api/projects');
            $form->text('address', '地址');
            $form->radio('type', '类型')->options(['1' => '住房', '2'=> '商业'])->default('1');
            $form->number('total_areas', '总面积（m²）');
            $form->text('remarks', '备注');

            $form->display('created_at', '创建时间');
            $form->display('updated_at', '更新时间');
        });
    }
}
