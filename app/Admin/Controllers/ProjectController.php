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
     * store data
     * 
     */
    public function store(){
       return 1;
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
            
            $form->text('name', '项目名称')->setWidth(9,3);
            $form->text('code', '项目编号');
            $form->number('total_household', '总户数');
            $form->number('total_areas', '总面积');
            $form->number('amount', '总金额');
            $form->text('created_at', '创建时间');
            $form->text('updated_at', '更新时间');
            $form->number('col_household', '应征补户数');
            $form->number('col_area_household', '应征补房屋面积');
            $form->number('col_amout_household', '房屋征补总金额');
            $form->number('excessive_amount', '过渡费');
            $form->number('col_amout_household', '房屋征补总金额');
            $form->number('col_amout_household', '土地征补总户数');
            $form->number('col_amout_household', '土地征补总面积');
            $form->number('col_amout_household', '土地征补总金额');
            $form->text('company_name', '中标公司名称');
            $form->number('price', '中标价格');
            $form->number('pay_price', '工程进度已支付金额');
            $form->number('agent_price', '招投标代理费');
            $form->number('pay_agent_price', '支付招投标代理费');
            $form->number('audit_price', '预算审计费用');
            $form->number('pay_audit_price', '支付预算审计费用');
            $form->number('design_price', '项目设计费');
            $form->number('balance_price', '支付项目设计费');
            $form->number('pay_balance_price', '结算审计费用');
            $form->number('pay_design_price', '支付结算审计费用');
            $form->number('settlement_price', '工程款结算审计金额');
            $form->number('pay_settlement_price', '支付工程款结算审计金额');
            $form->number('supervisor_price', '监理费用');
            $form->number('actul_supervisor_price', '支付监理费用');
            $form->number('warranty_price', '质保金额');
            $form->number('actul_warranty_price', '支付质保金额');
            
            
            $form->number('pya_col_household', '实际征补户数');
            $form->number('pya_col_area_household', '实际征补房屋面积');
            $form->number('pya_col_amout_household', '实际屋征补总金额');
            $form->number('pya_excessive_amount', '实际过渡费');
            $form->number('pya_col_amout_household', '实际房屋征补总金额');
            $form->number('pya_col_amout_household', '实际土地征补总户数');
            $form->number('pya_col_amout_household', '实际土地征补总面积');
            $form->number('pya_col_amout_household', '实际土地征补总金额');
            
            $form->setWidth(7,5);
            $form->setView('admin.project.edit');
            $form->number('total_areas', '总面积');
//             return view('admin.project.edit',compact('first','last'));

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
