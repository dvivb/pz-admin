<?php

namespace App\Admin\Controllers;

use App\Member;
use App\Project;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use App\Admin\Extensions\Importer;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Admin\Extensions\CustomExporter;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Collapse;
use Encore\Admin\Widgets\InfoBox;
use Encore\Admin\Widgets\Tab;
use Encore\Admin\Widgets\Table;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
//        echo 1;
        return Admin::content(function (Content $content) {

            $content->header('会员管理');
            $content->description('');

            $content->body($this->grid());
        });


//        return Admin::content(function (Content $content) {
//            $collapse = new Collapse();
//
//            $collapse->add('Bar', 'xxxxx');
//            $collapse->add('Orders', new Table());
//
////            echo $collapse->render();
//            $content->body($collapse);


//            $box = new Box('Box标题', 'Box内容');
//
//            $box->removable();
//
//            $box->collapsable();
//
//            $box->style('info');
//
//            $box->solid();
//
////            echo $box;
//
//            $content->body($box);


//            $form = new \Encore\Admin\Widgets\Form();
//            $form->setWidth(3, 2);
////            $form = $thisform();
//            $form->action('example');
//
//            $form->text('name', '名称');
//            $form->email('email', '邮箱');
//            $form->password('password', '密码');
//            $form->text('remember_token', 'Token');
//
//            $form->display('created_at', '创建时间');
//            $form->display('updated_at', '更新时间');
////            echo $form->render();
//
//            $content->body($form);


//            $tab = new Tab();
//
//            $tab->add('Pie', 111);
//            $tab->add('Table', new Table());
//            $tab->add('Text', 'blablablabla....');
//
////            echo $tab->render();
//
//            $content->body($tab);


//            $headers = ['Id', 'Email', 'Name', 'Company'];
//            $rows = [
//                [1, 'labore21@yahoo.com', 'Ms. Clotilde Gibson', 'Goodwin-Watsica'],
//                [2, 'omnis.in@hotmail.com', 'Allie Kuhic', 'Murphy, Koepp and Morar'],
//                [3, 'quia65@hotmail.com', 'Prof. Drew Heller', 'Kihn LLC'],
//                [4, 'xet@yahoo.com', 'William Koss', 'Becker-Raynor'],
//                [5, 'ipsa.aut@gmail.com', 'Ms. Antonietta Kozey Jr.'],
//            ];
//
//            $table = new Table($headers, $rows);
//
////            echo $table->render();
//
//            $content->body($table);


//            $content->row(function (Row $row) {
//
//                $row->column(4, 'xxx');
//
//                $row->column(8, function (Column $column) {
//                    $column->row('111');
//                    $column->row('222');
//                    $column->row('333');
//                });
//            });
//            $content->body();
//        });



    }

    /**
     * 储存一个新用户。
     *
     * @param  Request  $request
     * @return Response
     */
    public function update(Request $request)
    {
        $name = $request->input('name');
        return $name;

        //
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

            $content->header('会员管理');
            $content->description('');

            $content->body($this->form()->edit($id));
        });

//        return Admin::content(function (Content $content) {
//
//            // 选填
//            $content->header('填写页面头标题');
//
//            // 选填
//            $content->description('填写页面描述小标题');
//
//            // 填充页面body部分
//            $content->body('hello world');
//        });

//        return Admin::content(function (Content $content) {
//            $content->row(function(Row $row) {
//                $row->column(4, 'foo');
//                $row->column(4, 'bar');
//                $row->column(4, 'baz');
//            });
//        });

    }
//
//    /**
//     * Create interface.
//     *
//     * @return Content
//     */
//    public function create()
//    {
//        return Admin::content(function (Content $content) {
//
//            $content->header('会员管理');
//            $content->description('');
//
//            $content->body($this->form());
//        });
//    }


    /**
     *
     *
     */
    public function create(){
        var_dump($_GET);
        var_dump($_POST);
        echo 1;exit;
    }

    /**
     *
     *
     */
    public function save(){
        var_dump($_GET);
        var_dump($_POST);
        echo 1;exit;
    }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Member::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->name('名称');
            $grid->email('邮箱');
            $grid->remember_token('Token');

            $grid->created_at('创建时间');
            $grid->updated_at('更新时间');

            $grid->filter(function ($filter) {
                $filter->like('name', '名称');
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
        return Admin::form(Member::class, function (Form $form) {
            $form->setView('test');
        });
    }


}
