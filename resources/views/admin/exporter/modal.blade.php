<div class="btn-group pull-right" style="margin-right: 10px">
    <a href="#" target="_blank" class="btn btn-sm btn-twitter" data-toggle="modal"  data-target="#exporter-modal">
        <i class="fa fa-download"></i>&nbsp;&nbsp;{{ trans('admin::lang.export') }}
    </a>
</div>

<div class="modal fade" id="exporter-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    {{ trans('admin::lang.export') }}
                </h4>
            </div>
            <form action="{{ $export_url }}" class="export-form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-body">
                    <div class="form">
                        @foreach ($sheet_headers as $sheet_header_key => $sheet_header)
                            <div class="form-group">
                                @if (is_string($sheet_header))
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="options[]" value="{{ $sheet_header_key }}" checked>
                                            &nbsp;{{ $sheet_header }}&nbsp;&nbsp;
                                        </label>
                                    </div>
                                @elseif (is_array($sheet_header))
                                    <div class="checkbox">
                                        <label class="checkbox">
                                            <input type="checkbox" value="{{ $sheet_header_key }}" checked>
                                            &nbsp;{{ $sheet_header['name'] }}&nbsp;&nbsp;
                                        </label>

                                        @foreach ($sheet_header['_child'] as $val_key => $val)
                                            <label class="checkbox-inline" style="margin-left: 25px;">
                                                <input type="checkbox" name="options[]" value="{{ $sheet_header_key }}[{{ $val_key }}]" checked>
                                                &nbsp;{{ $val }}&nbsp;&nbsp;
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
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