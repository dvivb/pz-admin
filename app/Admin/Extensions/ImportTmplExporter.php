<?php

namespace App\Admin\Extensions;

use App\Placement;
use Closure;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Arr;

class ImportTmplExporter
{
    protected $tableName = '';
    protected $mode = 'general';
    protected $making = null;
    protected $model;

    public function __construct($model = '', $mode='general')
    {
        if ($model) {
            $this->model = $model;
            $this->tableName = uncamelize(substr($model, strrpos($model, '\\')+1));
        }

        if ($mode) {
            $this->mode = $mode;
        }
    }

    /**
     * Set making callback.
     *
     * @param Closure $callback
     *
     * @return void
     */
    public function making(Closure $callback)
    {
        $this->making = $callback;
    }

    /**
     * Call making callback.
     *
     * @return mixed
     */
    protected function callMaking()
    {
        if ($this->making instanceof Closure) {
            return call_user_func($this->making, $this);
        }
    }

    public function getData()
    {
        return factory($this->model, 2)->make();
    }

    public function export()
    {
        if ($this->mode === 'one_to_many') {
            $this->oneToManyExport();
        }
        elseif ($this->mode === 'general') {
            $this->generalExport();
        }
    }

    /**
     * 通用导出
     */
    public function generalExport()
    {
        $table = $this->tableName;
        $model = $this->model;

        $sheet_header = collect($model::getSheetHeader('import_tpl'));
        $data = $this->callMaking();
        if (!$data) {
            $data = $this->getData();
        }

        $formatted_data = $data->map(function ($value) use ($sheet_header) {
            return $sheet_header->keys()->mapWithKeys(function ($column) use ($sheet_header, $value) {
                return [$sheet_header->get($column) => $value[$column]];
            });
        })->all();

        Excel::create($model::translationTitle('import_tpl'), function($excel) use ($sheet_header, $formatted_data, $table, $model) {
            $excel->sheet($table, function($sheet) use ($sheet_header, $formatted_data, $table, $model) {
                $sheet->fromArray($formatted_data);
                $sheet->prependRow([$model::translationTitle('import_tpl')]);
                $sheet->mergeCells('A1:' . $this->getLetters($sheet_header->count() - 1) . '1');

                // 行高
                $height_general = 37;
                $body_height = collect(range(2, 10))
                    ->mapWithKeys(function ($_row) use ($sheet, $height_general, $sheet_header, $sheet) {
                        $sheet->row($_row, function($row) use ($_row, $sheet_header, $sheet) {
                            if ($_row === 2) {
                                $row->setBackground('#d8d8d8');
                            }

                            $row->setAlignment('center');
                            $row->setValignment('center');
                        });

                        return [$_row => $height_general];
                    })
                    ->prepend(53, 1)
                    ->all();
                $sheet->setHeight($body_height);

                // 列宽
                $width_general = 145 / 6;
                $body_width = collect(range(0, $sheet_header->count()-1))->mapWithKeys(function ($idx) use ($width_general) {
                    return [$this->getLetters($idx) => $width_general];
                })->all();
                $sheet->setWidth($body_width);

                // 表头配置
                $sheet->cell('A1', function ($cell) {
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBackground('#c1c1c1');
                    $cell->setFont([
                        'family'     => 'Calibri',
                        'size'       => '18',
                        'bold'       =>  true
                    ]);
                });
            });
        })->export('xlsx');

        exit;
    }

    /**
     * 一对多导出 TODO: wait_to_develop
     * {@inheritdoc}
     */
    public function oneToManyExport()
    {
        exit('Sorry, we haven\'t developed yet.');
    }

    /**
     * Remove indexed array.
     *
     * @param array $row
     *
     * @return array
     */
    protected function sanitize(array $row)
    {
        return collect($row)->reject(function ($val) {
            return is_array($val) && !Arr::isAssoc($val);
        })->toArray();
    }

    /**
     * 根据指定索引返回字母
     * @return \Illuminate\Support\Collection|mixed|string
     */
    private function getLetters()
    {
        $args = func_get_args(); // 传参列表
        $letter_collect = collect(['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',]);

        // 直取
        if (count($args) === 1) {
            if ($args[0] < 26) {
                $letter = $letter_collect->get($args[0]);
            } else {
                $multiple = intval($args[0] / $letter_collect->count());
                $letter_a = $letter_collect->get($multiple - 1);
                $letter_b = $letter_collect->get($args[0] % $letter_collect->count());
                $letter = $letter_a.$letter_b;
            }

            return $letter;
        }
        // 区间
        elseif (count($args) === 2) {
            $letters = collect();
            for ($i = $args[0]; $i <= $args[1]; $i++ ) {
                $letters->push($this->getLetters($i));
            }
            return $letters;
        }
    }
}