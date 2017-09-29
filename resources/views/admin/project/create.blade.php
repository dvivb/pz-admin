
<!-- right column -->
<div class="col-md-12">
    <!-- Horizontal Form -->
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">项目详情</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        {{--<form class="form-horizontal">--}}
        <form action="/admin/project/store" method="post" accept-charset="UTF-8" class="form-horizontal" pjax-container="">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="box-body">
                <div class="form-group col-md-12">
                    <label  class="col-sm-1 control-label">项目基本信息:</label>
                </div>

                <div class="form-group col-md-6">
                     {!! $form->fields()[0]->render() !!}
                </div>
                <div class="form-group col-md-6">
                     {!! $form->fields()[1]->render() !!}
                </div>
                <div class="form-group col-md-4">
                     {!! $form->fields()[2]->render() !!}
                </div>
                <div class="form-group col-md-4">
                    {!! $form->fields()[3]->render() !!}
                </div>
                <div class="form-group col-md-4">
                   {!! $form->fields()[4]->render() !!}
                </div>
            </div>
            <!-- /.box-body -->
            <!-- /.box-body -->
            <div class="box-body">
                
                <div class="form-group col-md-12">
                    <label  class="col-sm-1 control-label">征收补偿信息:</label>
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[5]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[6]->render() !!}
                </div>
                <div class="form-group col-md-3">
                     {!! $form->fields()[7]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[8]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[10]->render() !!}
                </div>
                
                <div class="form-group col-md-3">
                    {!! $form->fields()[11]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[12]->render() !!}
                </div>
  
                
            </div>
            <!-- /.box-body -->
            <!-- /.box-body -->
            <div class="box-body">
                <div class="form-group col-md-12">
                    <label  class="col-sm-1 control-label">项目工程信息:</label>
                </div>
                              
                
                <div class="form-group col-md-6">
                    {!! $form->fields()[13]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[14]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[15]->render() !!}
                </div>
                
                
                
                <div class="form-group col-md-3">
                    {!! $form->fields()[16]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[22]->render() !!}
                </div>
                <div class="form-group col-md-3">
                   {!! $form->fields()[18]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[20]->render() !!}
                </div>
                
                
                
                
                <div class="form-group col-md-3">
                    {!! $form->fields()[17]->render() !!}
                </div>
                
                <div class="form-group col-md-3">
                    {!! $form->fields()[23]->render() !!}
                </div>
                
                <div class="form-group col-md-3">
                    {!! $form->fields()[19]->render() !!}
                </div>
                <div class="form-group col-md-3">
                   {!! $form->fields()[21]->render() !!}
                </div>
                
                
                
                <div class="form-group col-md-3">
                    {!! $form->fields()[24]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[26]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[28]->render() !!}
                </div>
                <div class="form-group col-md-3">
                &nbsp;<br/><br/><br/>
                </div>
                
                
                <div class="form-group col-md-3">
                    {!! $form->fields()[25]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[27]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[29]->render() !!}
                </div>
                <div class="form-group col-md-3">
                &nbsp;<br/><br/><br/>
                </div>
            </div>
            <!-- /.box-body -->
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