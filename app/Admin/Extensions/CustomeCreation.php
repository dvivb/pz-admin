<?php

namespace App\Admin\Extensions;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;

class CustomeCreation extends AbstractTool
{
    /**
     * @var string
     */
    protected $href;

    public function __construct($href)
    {
        $this->href = $href;
    }

    /**
     * Render Export button.
     *
     * @return string
     */
    public function render()
    {
        $new_str = trans('admin::lang.new');

        return <<<EOT

<div class="btn-group pull-right" style="margin-right: 10px">
    <a href="$this->href" id="custom_creation-btn" class="btn btn-sm btn-success">
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