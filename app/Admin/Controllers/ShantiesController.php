<?php

namespace App\Admin\Controllers;

use App\Shanties;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Admin\Extensions\Importer;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Admin\Extensions\CustomExporter;
use Illuminate\Database\Eloquent\Model;
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

//            $form->text('created_at', '创建时间');
//            $form->text('updated_at', '更新时间');
//            $form->number('col_household', '应征补户数');
//            $form->number('col_area_household', '应征补房屋面积');
//            $form->number('col_amout_household', '房屋征补总金额');
//            $form->number('excessive_amount', '过渡费');
//            $form->number('col_amout_household', '房屋征补总金额');
//            $form->number('col_land_household', '土地征补总户数');
//            $form->number('col_land_areas', '土地征补总面积');
//            $form->number('col_area_amout', '土地征补总金额');
//            $form->text('company_name', '中标公司名称');
//            $form->number('price', '中标价格');
//            $form->number('pay_price', '工程进度已支付金额');
//            $form->number('agent_price', '招投标代理费');
//            $form->number('pay_agent_price', '支付招投标代理费');
//            $form->number('audit_price', '预算审计费用');
//            $form->number('pay_audit_price', '支付预算审计费用');
//            $form->number('design_price', '项目设计费');
//            $form->number('balance_price', '支付项目设计费');
//            $form->number('pay_balance_price', '结算审计费用');
//            $form->number('pay_design_price', '支付结算审计费用');
//            $form->number('settlement_price', '工程款结算审计金额');
//            $form->number('pay_settlement_price', '支付工程款结算审计金额');
//            $form->number('supervisor_price', '监理费用');
//            $form->number('actul_supervisor_price', '支付监理费用');
//            $form->number('warranty_price', '质保金额');
//            $form->number('actul_warranty_price', '支付质保金额');
//
//
//            $form->number('actual_household', '实际征补户数');
//            $form->number('actual_area_household', '实际征补房屋面积');
//            $form->number('actual_amout_household', '实际房屋征补总金额');
//            $form->number('actual_excessive_amount', '实际过渡费');
//            $form->number('actual_amout_household', '实际房屋征补总金额');
//            $form->number('actual_land_hosehold', '实际土地征补总户数');
//            $form->number('actual_land_areas', '实际土地征补总面积');
//            $form->number('actual_area_amount', '实际土地征补总金额');
//            $form->number('id', 'id');

//            $form->setWidth(7,5);
            $form->setView('admin.shanties.edit');
//            $form->number('total_areas', '总面积');

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