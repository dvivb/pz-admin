
<!-- right column -->
<div class="col-md-12">
    <!-- Horizontal Form -->
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">新增棚改安置房信息</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        {{--<form class="form-horizontal">--}}
        <form action="/admin/shanties/store" method="post" accept-charset="UTF-8" class="form-horizontal" pjax-container="">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="box-body">
                <div class="form-group col-md-12">
                     {!! $form->fields()[0]->render() !!}
                </div>
                <div class="form-group col-md-12">
                     {!! $form->fields()[1]->render() !!}
                </div>
                <div class="form-group col-md-6">
                     {!! $form->fields()[2]->render() !!}
                </div>
                <div class="form-group col-md-6">
                    {!! $form->fields()[3]->render() !!}
                </div>
                <div class="form-group col-md-6">
                   {!! $form->fields()[4]->render() !!}
                </div>
                <div class="form-group col-md-6">
                   {!! $form->fields()[5]->render() !!}
                </div>
                <div class="form-group col-md-12">
                    {!! $form->fields()[6]->render() !!}
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-default">取消</button>
                <button type="submit" class="btn btn-info pull-right">提交</button>
            </div>
            <!-- /.box-footer -->
        </form>
    </div>
    <!-- /.box -->
</div>
<!--/.col (right) -->