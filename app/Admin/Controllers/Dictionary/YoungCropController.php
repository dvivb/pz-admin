<?php

namespace App\Admin\Controllers\Dictionary;

use App\Dictionary;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class YoungCropController extends Controller
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

            $content->header('青苗');
            $content->description('字典表');

            $content->body(Dictionary::tree(function ($tree) {
                $tree->query(function ($model) {
                    return $model->where('subject', 'young_crop');
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

            $content->header('青苗');
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

            $content->header('青苗');
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
            $grid->unit('单位');
            $grid->price('补偿标准');
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
            $form->hidden('subject')->value('young_crop'); // 主题

            $form
                ->select('parent_id', '父级')
                ->options(function ($id) {
                    $dictionary = Dictionary::find($id);

                    if ($dictionary) {
                        return [$dictionary->id => $dictionary->name];
                    }
                })
                ->ajax('/admin/api/dictionaries/young_crop');

            $form->text('name', '名称');
            $form->text('unit', '单位');
            $form->currency('price', '补偿标准');
            $form->text('remarks', '备注');


            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
