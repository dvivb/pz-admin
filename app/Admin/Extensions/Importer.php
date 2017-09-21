<?php

namespace App\Admin\Extensions;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use App\Admin\Extensions\Importer\Field\File;
use Maatwebsite\Excel\Facades\Excel;

class Importer extends AbstractTool
{
    /**
     * @var string
     */
    protected $view = 'admin.importer.modal';

    protected $target;

    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
        $this->target = uncamelize(substr($model, strrpos($model, '\\')+1));
    }

    protected function script()
    {
        return <<<EOT

$("#importer-modal .submit").click(function () {
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
});
    
EOT;

    }

    /**
     * Select filter.
     *
     * @param array $options
     *
     * @return $this
     */
    public function file($column, $arguments = [])
    {
        $file = new File($column, $arguments);
        $file->rules('xlsx');

        return $file;
    }



    /**
     * Render Export button.
     *
     * @return string
     */
    public function render()
    {
        Admin::script($this->script());

        $filters[] = $this->file('ul_fl', ['导入文件']);

        return view($this->view)->with([
            'target' => $this->target,
            'filters' => $filters,
            'action' => '/admin/api/import/' . $this->target
        ]);
    }

    /**
     * 导入接口
     * @param $path
     * @return mixed
     */
    public function importFace($path)
    {
        return $this->import($this->pack($path));
    }

    public static function getSkipRows($standard)
    {
        $is_many = 0;
        collect($standard)->each(function ($column_data) use (&$is_many) {
            if (is_array($column_data)) {
                $is_many = 1;

                return false;
            }
        });

        return $is_many === 1 ? 3 : 2;
    }

    /**
     * 导入数据
     * @param $pack_data
     * @return mixed
     */
    public function import($pack_data)
    {
        $model = $this->model;
        // 自定义导入
        if (method_exists($model, 'import')) {
            return $model::import($pack_data);
        }
        // 全局导入
        else
        {
            $pack_data->each(function ($value) use ($model) {
                $model::create($value->all());
            });

            return true;
        }
    }

    /**
     * 组装导入数据
     * @param $path
     * @return \Illuminate\Support\Collection
     */
    public function pack($path)
    {
        $model = $this->model;
        // 导入格式标准
        if (method_exists($model, 'getImportStandard')) {
            $standard = $model::getImportStandard();
        } else {
            $standard = $model::$importStandard;
        }

        $pack_data = collect();
        Excel::load($path, function($reader) use ($standard, &$pack_data) {
            $reader->noHeading();
            $reader
                ->skipRows(self::getSkipRows($standard))
                ->each(function($row) use ($standard, &$pack_data) {
                    if (is_null($row) || $row->filter()->isEmpty()) return ;

                    $is_many = 0;
                    $column_data = collect($standard)->mapWithKeys(function ($standard_sg, $standard_sg_k) use ($row, &$is_many) {
                        if (is_array($standard_sg)) {
                            $standard_sg_rk = array_keys($standard_sg)[0];
                            $tem_pack_data = collect();
                            collect($standard_sg[$standard_sg_rk])->map(function ($standard_sg_2, $standard_sg_k_2) use ($tem_pack_data, $row) {
                                $tem_pack_data->put($standard_sg_2, $row->get($standard_sg_k_2));
                            });

                            $is_many = $is_many ? : 1;
                            return [$standard_sg_rk => [$tem_pack_data->all()]];
                        } else {
                            if ($row->get($standard_sg_k)) {
                                $is_many = 2;
                            }

                            return [$standard_sg => $row->get($standard_sg_k)];
                        }
                    });
                    if ($is_many === 1) {
                        $to_many_tem = $column_data->filter();
                        $pack_data->last()->transform(function ($item, $key) use ($to_many_tem) {
                            if ($to_many_tem->get($key)) {
                                array_push($item, $to_many_tem->get($key)[0]);
                            }
                            return $item;
                        });
                    } else {
                        $pack_data->push($column_data);
                    }
                });
        });

        return $pack_data;
    }
}