<?php

namespace App\Admin\Controllers;
//namespace App\Http\Controllers\Admin;

use App\Houses;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;

use Encore\Admin\Widgets\InfoBox;
use Illuminate\Http\Request;
use Encore\Admin\Form;
use Encore\Admin\Grid;



use Illuminate\Support\Facades\Redirect, Illuminate\Support\Facades\Input, Encore\Admin\Auth;

class HousesController extends Controller {

    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('房屋征收');
            $content->description('');

            $content->body($this->grid());
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('房屋征收');
            $content->description('');
            $content->body($this->form());
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Houses::class, function (Form $form) {
            $form->setView('admin.houses.create');
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
//    public function store(Request $request)
    public function store()
    {
//                    return Redirect::to('admin/houses');
//        var_dump($_POST);die;
//        var_dump($request);
//        $this->validate($request, [
//            'title' => 'required|unique:pages|max:255',
//            'body' => 'required',
//        ]);
//
        $page = new Houses;
        $page->title = Input::get('title');
        $page->body = Input::get('body');
//        $page->user_id = 1;//Auth::user()->id;

        if ($page->save()) {
            return Redirect::to('admin/houses');
        } else {
            return Redirect::back()->withInput()->withErrors('保存失败！');
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
//        return view('admin.houses.edit')->withPage(Page::find($id));
        return Admin::content(function (Content $content) use ($id) {

            $content->header('会员管理');
            $content->description('');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request,$id)
    {
//        $this->validate($request, [
//            'title' => 'required|unique:houses,title,'.$id.'|max:255',
//            'body' => 'required',
//        ]);

        $page = Houses::find($id);
        $page->title = Input::get('title');
        $page->body = Input::get('body');
//        $page->user_id = 1;//Auth::user()->id;

        if ($page->save()) {
            return Redirect::to('admin/houses');
        } else {
            return Redirect::back()->withInput()->withErrors('保存失败！');
        }
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Houses::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->title('标题');
            $grid->body('内容');

            $grid->created_at('创建时间');
            $grid->updated_at('更新时间');

            $grid->filter(function ($filter) {
                $filter->like('title', '名称');
            });

        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $page = Page::find($id);
        $page->delete();

        return Redirect::to('admin');
    }

}