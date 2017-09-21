<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\CustomExporter;
use App\Admin\Extensions\Importer;
use App\Transition;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class TransitionController extends Controller
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

            $content->header('过渡费发放');
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

            $content->header('过渡费发放');
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

            $content->header('过渡费发放');
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
        return Admin::grid(Transition::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->name('姓名');
            $grid->id_number('身份证号码');
            $grid->villages('乡镇');
            $grid->address('住址')->limit(10);
            $grid->contact('联系电话');
            $grid->signed_at('签约时间');
            $grid->living_area('居住面积');
            $grid->living_price('居住过渡费');
            $grid->business_area('商业面积');
            $grid->business_price('商业过渡费');

            $grid->filter(function ($filter) {
                $filter->useModal();
            });


            $grid->exporter(new CustomExporter(null, Transition::class));

            $grid->tools(function ($tools) use ($grid) {
                $tools->append(new Importer(Transition::class));
            });

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
        return Admin::form(Transition::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->text('name', '姓名');
            $form->text('id_number', '身份证号码');
            $form->text('villages', '乡镇');
            $form->text('address', '住址');
            $form->text('contact', '联系电话');
            $form->date('signed_at', '签约时间');
            $form->number('living_area', '居住面积');
            $form->currency('living_price', '居住过渡费');
            $form->number('business_area', '商业面积');
            $form->currency('business_price', '商业过渡费');
            $form->currency('amount', '总过渡费');
            $form->date('started_at', '起始时间');
            $form->date('ended_at', '起止时间');
            $form->text('signed', '签名');
            $form->date('outed_at', '发放时间');
            $form->textarea('remarks', '备注');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
