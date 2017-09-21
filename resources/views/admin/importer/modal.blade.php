<div class="btn-group pull-right " style="margin-right: 10px">
    <a href="#" target="_blank" class="btn btn-sm fc-button importer-btn" data-toggle="modal"  data-target="#importer-modal">
        <i class="fa fa-upload"></i>&nbsp;&nbsp;{{ trans('admin.import') }}
    </a>
</div>

<div class="modal fade" id="importer-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    {{ trans('admin.import') }}
                    <a href="/admin/api/import_templates/{{ $target }}" target="_blank" class="btn btn-sm btn-link import_template_dl-btn" style="padding: 0;margin-left: 6px;"> <i class="fa fa-file-excel-o"></i>  点击下载导入模板 </a>
                </h4>
            </div>
            <form action="{!! $action !!}" enctype="multipart/form-data" method="post" pjax-container>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-body">
                    <div class="form">
                        @foreach($filters as $filter)
                            <div class="form-group">
                                {!! $filter->render() !!}
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> {{ trans('admin::lang.submit') }}" >{{ trans('admin::lang.submit') }}</button>
                    <button type="reset" class="btn btn-warning pull-left">{{ trans('admin::lang.reset') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

