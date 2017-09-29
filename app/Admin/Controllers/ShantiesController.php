<?php

namespace App\Admin\Controllers;

use App\Shanties;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;


class ShantiesController extends Controller {

    public static $header         = '棚改安置房';
    public static $description    = '';

    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header(self::$header);
            $content->description(self::$description);

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

            $content->header(self::$header);
            $content->description(self::$description);

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

            $content->header(self::$header);
            $content->description(self::$description);

            $content->body($this->formCreate());
        });
    }

    /**
     * store data
     *
     */
    public function store(Request $request){
        if(empty($_POST)){
            goto Label;
        }
        if($request->input('id') > 0){
            $model = Shanties::find($request->input('id'));
        }else{
            $model = new Shanties;
        }

//        $page->user_id = 1;//Auth::user()->id;
        foreach($_POST as $k=>$v){
            if($k =='_token')continue;
            $model->$k = $request->input($k,'0');
        }
        if ($model->save()) {
            return Redirect::to('admin/shanties');
        } else {
            Label:
            return Redirect::back()->withInput()->withErrors('保存失败！');
        }
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Shanties::class, function (Form $form) {

            $form->text('name', '项目名称');
            $form->text('address', '地址');
            $form->number('house_total', '住房套数');
            $form->number('house_area', '住房面积');
            $form->number('biz_house_total', '商业间数');

            $form->number('biz_house_area', '商业面积');
            $form->number('house_month_total', '本月（套）');
            $form->number('house_year_total', '本年度（套）');
            $form->number('house_year_area', '本年度累计面积(平面)');
            $form->number('house_amount', '总累计（套）');

            $form->number('house_amount_area', '总累计（平方）');
            $form->number('house_surplusr_amount', '剩余套数');
            $form->number('biz_house_month_total', '本月（套）');
            $form->number('biz_house_year_total', '本年度（套）');
            $form->number('biz_house_year_area', '本年度累计面积(平面)');

            $form->number('biz_house_amount', '总累计（套）');
            $form->number('biz_house_amount_area', '总累计（平方）');
            $form->number('biz_house_surplusr_amount', '剩余套数');
            $form->text('remarks', '备注');
            $form->text('operator', '填报人');

            $form->text('created_at', '填报时间');
            $form->text('updated_at', '更新时间');

            $form->setWidth(7,3);
            $form->setView('admin.shanties.edit');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function formCreate()
    {
        return Admin::form(Shanties::class, function (Form $form) {
            $form->text('name', '项目名称');
            $form->text('address', '地址');
            $form->number('house_total', '住房套数');
            $form->number('house_area', '住房面积');
            $form->number('biz_house_total', '商业间数');

            $form->number('biz_house_area', '商业面积');
            $form->text('remarks', '备注');

            $form->setWidth(7,3);
            $form->setView('admin.shanties.create');
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Shanties::class, function (Grid $grid) {

            $grid->id('序号')->sortable();
            $grid->name('项目名称')->sortable();
            $grid->address('地址')->sortable();
            $grid->house_total('住房套数')->sortable();
            $grid->house_area('住房面积')->sortable();
            $grid->biz_house_area('商业面积')->sortable();
            $grid->house_surplusr_amount('剩余套数')->sortable();
            $grid->biz_house_area('商业面积')->sortable();
            $grid->updated_at('更新时间');

            $grid->filter(function ($filter) {
                $filter->like('name', '名称');
            });
        });
    }


}