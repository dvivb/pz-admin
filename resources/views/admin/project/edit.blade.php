
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
        <form action="http://pz.admin.com/admin/houses/store" method="post" accept-charset="UTF-8" class="form-horizontal" pjax-container="">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            
            
            <div class="fields-group" style="display:none">
                    @foreach($form->fields() as $field)
                        {!! $field->render() !!}
                    @endforeach
                </div>
            
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
                <div class="form-group col-md-4">
                    {!! $form->fields()[5]->render() !!}
                </div>
                <div class="form-group col-md-4">
                    {!! $form->fields()[6]->render() !!}
                </div>
                
                <div class="form-group col-md-4">
                    <label for="inputPassword3" class="col-sm-3 control-label">修改用户</label>

                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="amount"  placeholder="修改用户">
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <!-- /.box-body -->
            <div class="box-body">
                <div class="form-group col-md-12">
                    <label  class="col-sm-1 control-label">征收补偿信息:</label>
                </div>
                <div class="form-group col-md-3">
                     {!! $form->fields()[7]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[8]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[9]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[10]->render() !!}
                </div>
                
                <div class="form-group col-md-3">
                    {!! $form->fields()[32]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[33]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[34]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[35]->render() !!}
                </div>
                
                
                
                <div class="form-group col-md-3">
                     {!! $form->fields()[11]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[12]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[13]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[14]->render() !!}
                </div>
                
                 <div class="form-group col-md-3">
                     {!! $form->fields()[36]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[37]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[38]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[39]->render() !!}
                </div>
                
                
                
            </div>
            <!-- /.box-body -->
            <!-- /.box-body -->
            <div class="box-body">
                <div class="form-group col-md-12">
                    <label  class="col-sm-1 control-label">项目工程信息:</label>
                </div>
                <div class="form-group col-md-6">
                    {!! $form->fields()[15]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[16]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[17]->render() !!}
                </div>
                
                
                <div class="form-group col-md-3">
                   {!! $form->fields()[18]->render() !!}
                </div>
                
                <div class="form-group col-md-3">
                    {!! $form->fields()[20]->render() !!}
                </div>
                
                <div class="form-group col-md-3">
                    {!! $form->fields()[22]->render() !!}
                </div>
                
                <div class="form-group col-md-3">
                    {!! $form->fields()[24]->render() !!}
                </div>
                
                <div class="form-group col-md-3">
                    {!! $form->fields()[19]->render() !!}
                </div>
                <div class="form-group col-md-3">
                   {!! $form->fields()[21]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[23]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[25]->render() !!}
                </div>
                
                
                <div class="form-group col-md-3">
                    {!! $form->fields()[26]->render() !!}
                </div>
               
                <div class="form-group col-md-3">
                    {!! $form->fields()[28]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[30]->render() !!}
                </div>
                <div class="form-group col-md-3">
                &nbsp;<br/><br/><br/>
                </div>
                
                <div class="form-group col-md-3">
                    {!! $form->fields()[27]->render() !!}
                </div>
                <div class="form-group col-md-3">
                    {!! $form->fields()[29]->render() !!}
                </div>
                
                <div class="form-group col-md-3">
                    {!! $form->fields()[31]->render() !!}
                </div>
                <div class="form-group col-md-3">
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-default">Cancel</button>
                <button type="submit" class="btn btn-info pull-right">Sign in</button>
            </div>
            <!-- /.box-footer -->
        </form>
    </div>
    <!-- /.box -->
</div>
<!--/.col (right) -->