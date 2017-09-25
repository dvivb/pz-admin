<?php

namespace App\Admin\Controllers;

use App\Project;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Admin\Extensions\Importer;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Admin\Extensions\CustomExporter;

class ProjectController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('项目管理');
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

            $content->header('项目管理');
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

            $content->header('项目管理');
            $content->description('');

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
        return Admin::grid(Project::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->name('名称');
            $grid->total_household('总户数');
            $grid->total_areas('总面积');
            $grid->amount('总金额');

            $grid->created_at('创建时间');
            $grid->updated_at('更新时间');

            $grid->filter(function ($filter) {
                $filter->like('name', '名称');
            });

            $grid->exporter(new CustomExporter(null, Project::class));

            $grid->tools(function ($tools) use ($grid) {
                $tools->append(new Importer(Project::class));
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
        return Admin::form(Project::class, function (Form $form) {
            $form->setView('admin.project.edit');

//             $form->display('id', 'ID');

//             $form->text('name', '名称');
//             $form->number('total_household', '总户数');
//             $form->number('total_areas', '总面积');
//             $form->number('col_household', '应该征补户数');
//             $form->currency('amount', '总金额');

//             $form->display('created_at', '创建时间');
//             $form->display('updated_at', '更新时间');
        });
    }
}
