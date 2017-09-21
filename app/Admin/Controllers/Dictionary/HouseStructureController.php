<?php

namespace App\Admin\Controllers\Dictionary;

use App\Dictionary;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class HouseStructureController extends Controller
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

            $content->header('房屋结构');
            $content->description('字典表');

            $content->body(Dictionary::tree(function ($tree) {
                $tree->query(function ($model) {
                    return $model->where('subject', 'house_structure');
                });
            }));
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

            $content->header('房屋结构');
            $content->description('字典表');

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

            $content->header('房屋结构');
            $content->description('字典表');

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
        return Admin::grid(Dictionary::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->name('名称');
            $grid->price('金额');
            $grid->remarks('备注');

            $grid->created_at();
            $grid->updated_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Dictionary::class, function (Form $form) {


            $form->display('id', 'ID');
            $form->hidden('subject')->value('house_structure'); // 主题

            $form
                ->select('parent_id', '父级')
                ->options(function ($id) {
                    $dictionary = Dictionary::find($id);

                    if ($dictionary) {
                        return [$dictionary->id => $dictionary->name];
                    }
                })
                ->ajax('/admin/api/dictionaries/house_structure');

            $form->text('name', '名称');
            $form->text('unit', '单位')->default('m²');
            $form->currency('price', '金额');
            $form->text('remarks', '备注');


            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
