<?php

namespace App\Admin\Extensions;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;

class PeriodCreation extends AbstractTool
{
    /**
     * @var string
     */
    protected $href;
    protected $data;

    public function __construct($href, $data)
    {
        $this->href = $href;
        $this->data = $data;
    }

    public function script()
    {
        $data = json_encode(array_merge(['_token' => csrf_token()], $this->data));
        return <<<EOT
        
$('#custom_creation-btn').on('click', function () {
    $.post('/admin/$this->href', $data, function (data) {
        $.pjax.reload('#pjax-container');
        toastr.success('新增成功 !');
    });
});
EOT;



    }

    /**
     * Render Export button.
     *
     * @return string
     */
    public function render()
    {
        Admin::script($this->script());

        $new_str = trans('admin::lang.new');
        return <<<EOT

<div class="btn-group pull-right" style="margin-right: 10px">
    <a id="custom_creation-btn" class="btn btn-sm btn-success">
        <i class="fa fa-save"></i> $new_str
    </a>
</div>
EOT;
    }

    public function __toString()
    {
        return $this->render();
    }
}