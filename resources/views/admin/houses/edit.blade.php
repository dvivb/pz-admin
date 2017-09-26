<!-- right column -->
<div class="col-md-12">
    <!-- Horizontal Form -->
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">项目详情</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        {{ var_dump(model) }}
        {{--<form class="form-horizontal">--}}
        <form action="http://pz.admin.com/admin/houses/store" method="post" accept-charset="UTF-8" class="form-horizontal" pjax-container="">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="box-body">
                <div class="form-group col-md-12">
                    <label  class="col-sm-1 control-label">项目基本信息:</label>
                </div>
                <div class="form-group col-md-6">
                    <label for="inputEmail3" class="col-sm-2 control-label">项目名称</label>

                    <div class="col-sm-10">
                        <input type="text" value="11111" class="form-control"  name="name"  placeholder="项目名称">
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="inputPassword3" class="col-sm-2 control-label">项目编号</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="code"  placeholder="项目编号">
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label for="inputPassword3" class="col-sm-3 control-label">总户数</label>

                    <div class="col-sm-9">
                        <input type="number" class="form-control" name="total_household"  placeholder="总户数">
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label for="inputPassword3" class="col-sm-3 control-label">总面积</label>

                    <div class="col-sm-9">
                        <input type="number" class="form-control" name="total_areas"  placeholder="总面积">
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label for="inputPassword3" class="col-sm-3 control-label">总金额</label>

                    <div class="col-sm-9 ">
                        <input type="number" class="form-control" name="amount"  placeholder="总金额">
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label for="inputPassword3" class="col-sm-3 control-label">创建时间</label>

                    <div class="col-sm-9">
                        <input type="number" class="form-control" name="created_at"  placeholder="创建时间">
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label for="inputPassword3" class="col-sm-3 control-label">修改时间</label>

                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="updated_at"  placeholder="修改时间">
                    </div>
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
                    <label for="inputEmail3" class="col-sm-5 control-label">房屋征补总户数</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control"  name="name"  placeholder="房屋征补总户数">
                    </div>
                </div>
                <div class="form-group col-md-3">
                    <label for="inputPassword3" class="col-sm-5 control-label">房屋征补总面积</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="code"  placeholder="房屋征补总面积">
                    </div>
                </div>
                <div class="form-group col-md-3">
                    <label for="inputPassword3" class="col-sm-5 control-label">房屋征补总金额</label>

                    <div class="col-sm-7">
                        <input type="number" class="form-control" name="total_household"  placeholder="房屋征补总金额">
                    </div>
                </div>
                <div class="form-group col-md-3">
                    <label for="inputPassword3" class="col-sm-5 control-label">过渡费总计</label>

                    <div class="col-sm-7">
                        <input type="number" class="form-control" name="total_areas"  placeholder="过渡费总计">
                    </div>
                </div>
                <div class="form-group col-md-3">
                    <label for="inputPassword3" class="col-sm-5 control-label">土地征补总户数</label>

                    <div class="col-sm-7">
                        <input type="number" class="form-control" name="amount"  placeholder="土地征补总户数">
                    </div>
                </div>
                <div class="form-group col-md-3">
                    <label for="inputPassword3" class="col-sm-5 control-label">土地征补总面积</label>

                    <div class="col-sm-7">
                        <input type="number" class="form-control" name="created_at"  placeholder="土地征补总面积">
                    </div>
                </div>
                <div class="form-group col-md-3">
                    <label for="inputPassword3" class="col-sm-5 control-label">土地征补总金额</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="updated_at"  placeholder="土地征补总金额">
                    </div>
                </div>

                <div class="form-group col-md-3">
                    <label for="inputPassword3" class="col-sm-5 control-label">修改用户</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="amount"  placeholder="修改用户">
                    </div>
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