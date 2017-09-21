<?php

namespace App\Admin\Extensions;

use Closure;
use Carbon\Carbon;
use Encore\Admin\Admin;
use Encore\Admin\Grid\Exporters\AbstractExporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Arr;
use Encore\Admin\Grid;

class CustomExporter extends AbstractExporter
{
    protected $tableName = '';
    protected $mode = 'general';
    protected $making = null;
    protected $model;

    public function __construct(Grid $grid = null, $model = '', $mode='')
    {
        parent::__construct($grid);

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

    public function export()
    {
        if ($this->mode === 'one_to_many') {
            $this->oneToManyExport();
        }
        elseif ($this->mode === 'general') {
            $this->generalExport();
        }
    }

    public function generalExport()
    {
        $table = $this->tableName;
        $model = $this->model;

        $sheet_header = collect($model::getSheetHeader('export'));

        $data = $this->callMaking();
        if (!$data) {
            $data = $this->getData();
        }

        $formatted_data = collect($data)->map(function ($value) use ($sheet_header) {
            return $sheet_header->keys()->mapWithKeys(function ($column) use ($sheet_header, $value) {
                return [$sheet_header->get($column) => $value[$column]];
            });
        })->all();

        Excel::create($model::translationTitle('export'), function($excel) use ($sheet_header, $formatted_data, $table, $model) {
            $excel->sheet($table, function($sheet) use ($sheet_header, $formatted_data, $table, $model) {
                $sheet->fromArray($formatted_data);
                $sheet->prependRow([$model::translationTitle('export')]);
                $sheet->mergeCells('A1:' . $this->getLetters($sheet_header->count() - 1).'1');

                $sheet->row('2', function($row){
                    $row->setBackground('#2c8e50');
                    $row->setAlignment('center');
                    $row->setValignment('center');
                });

                // 行高
                $sheet->setHeight(array(
                    1  =>  33,
                    2  =>  23
                ));

                $sheet->cell('A1', function ($cell) {
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('thin', 'thin', 'thin', 'thin');
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
     * {@inheritdoc}
     */
    public function oneToManyExport()
    {
        $table = $this->tableName;
        $model = $this->model;

        $sheet_header = collect($model::getSheetHeader('export'));

        $sheet_width = [];
        if (method_exists($model, 'getSheetWidth')) {
            $sheet_width = collect($model::getSheetWidth('export'));
        }

        $data = $this->callMaking();
        if (!$data) {
            $data = $this->getData();
        }

        // 格式化导出数据
        $formatted_data = collect($data)->map(function ($data) use ($sheet_header) {
            return
                $sheet_header->keys()->map(function ($item) use ($data, $sheet_header) {
                    $value = $sheet_header->get($item);
                    if (is_array($value)) {
                        return [
                            $item =>
                                collect($data[$value['signed']])
                                    ->where('subject', $item)
                                    ->map(function ($column) use ($value, $item) {
                                        return
                                            collect($value['_child'])->keys()->mapWithKeys(function ($value) use ($column) {
                                                return [$value => $column[$value]];
                                            })->all();
                                    })
                                    ->values()
                                    ->all() ? : [count($value['_child'])]
                        ];
                    }
                    elseif (isset($data[$item])) {
                        return [$item => $data[$item]];
                    }
                })->collapse()->all();
        });

        // Header set
        Excel::create($model::translationTitle('export'), function($excel) use ($table, $sheet_header, $formatted_data, $model, $sheet_width) {

            $excel->sheet($table, function($sheet) use ($sheet_header, $formatted_data, $model, $sheet_width) {
                // Set auto size for sheet
                $sheet->setAutoSize(true);

                $width_global = 32;  // 列宽
                $height_global = 37; // 行高

                $sheet_header_c = 0;
                $letters = collect(['column_names' => collect() , 'general' => collect()]);

                // 整理表头
                $sheet_header->keys()->each(function ($item, $key) use ($sheet, $sheet_header, &$letters, &$sheet_header_c, $sheet_width) {
                    $sheet_header_c = $sheet_header_c ?: $key;
                    $value = $sheet_header->get($item);

                    if (is_array($value) && isset($value['_child'])) {
                        $c = count($value['_child']);

                        $letter_groups = $this->getLetters($sheet_header_c, $sheet_header_c+$c-1);

                        $sheet->mergeCells($letter_groups->first() . '1' . ':' . $letter_groups->last() . '1');
                        $sheet->cell($letter_groups->first() . '1', function ($cell) use ($value) {
                            $cell->setValue($value['name']);
                            $cell->setAlignment('center');
                            $cell->setValignment('center');
                        });

                        $letter_groups->map(function ($letter, $key) use ($sheet, $value, $item, $sheet_width) {
                            $child_column = collect($value['_child'])->keys()->get($key);

                            // 设置列宽
                            if ($sheet_width) {
                                if (!array_key_exists('__reuse', $sheet_width->get($item))) {
                                    $sheet->setWidth($letter, $sheet_width->get($item)[$child_column]);
                                } else {
                                    $sheet->setWidth($letter, $sheet_width->get($sheet_width->get($item)['__reuse'])[$child_column]);
                                }
                            }

                            $sheet->cell($letter . '2', function ($cell) use ($value, $key, $child_column) {
                                $cell->setValue($value['_child'][$child_column]);
                                $cell->setAlignment('center');
                                $cell->setValignment('center');
                            });
                        });

                        $sheet_header_c += $c;
                    } else {
                        $letter_single = $this->getLetters($sheet_header_c);

                        // 设置列宽
                        if ($sheet_width) {
                            $sheet->setWidth($letter_single, $sheet_width->get($item));
                        }


                        $letters->get('general')->push($letter_single);
                        $letters->get('column_names')->push($value);

                        $sheet_header_c++;
                    }
                });

                $sheet->setMergeColumn([
                    'columns' => $letters->get('general')->all(),
                    'rows' => [
                        [1,2],
                    ]
                ]);

                $letters->get('general')->each(function ($column, $key) use ($sheet, $letters) {
                    $sheet->cell($column . '1', function ($cell) use ($key, $letters) {
                        $cell->setValue($letters->get('column_names')->get($key));
                        $cell->setAlignment('center');
                        $cell->setValignment('center');
                    });
                });

                $row_rs = 0;
                $row_re = 0;

                // 整理内容主体
                $formatted_data->each(function ($column_data, $key) use ($sheet, $sheet_header, $letters, &$row_rs, &$row_re) {
                    $max_c = 0;
                    $merges = collect(['row' => collect()]);
                    $column_r = 0;
                    $row_r = $row_rs = ($row_rs && $row_re) ? $row_re+1 : $key + 3;

                    collect($column_data)->each(function ($value, $k_2) use ($sheet, $sheet_header, &$row_r, &$column_r, &$max_c) {
                        if (is_array($value)) {
                            $c = count($value);
                            $max_c = $c > $max_c ? $c : $max_c;
                            $column_r += is_array($value[0]) ? count($value[0]) : $value[0];
                            $row_r_2 = $row_r;

                            collect($value)->each(function ($val_2) use ($sheet, &$row_r_2, $column_r, $k_2) {
                                if (is_int($val_2)) {
                                    return false;
                                }

                                $column_r_2 = $column_r - count($val_2);

                                // dump('S3');

                                collect($val_2)->each(function ($val_3) use ($sheet, &$column_r_2, $row_r_2, $k_2) {

                                    // dump('S4 - ' . $column_r_2 . ':' . $row_r_2 .  ' - '. $val_3);

                                    $sheet->cell($this->getLetters($column_r_2) . $row_r_2, function ($cell) use ($val_3) {
                                        $cell->setValue($val_3);
                                        $cell->setAlignment('center');
                                        $cell->setValignment('center');
                                    });

                                    $column_r_2 ++;
                                });

                                $row_r_2 ++;

                                // dump('E3');
                            });

                            // dump('E2');

                        } else {
                            $column_r ++;
                        }
                    });

                    // dd('end temporary');

                    $row_re = $row_rs + $max_c - 1;
                    $sheet->setMergeColumn([
                        'columns' => $letters->get('general')->all(),
                        'rows' => [
                            [$row_rs, $row_re],
                        ]
                    ]);

                    $row_rr = 0;
                    collect($column_data)->each(function ($value, $k2) use ($sheet, &$row_rr, $row_rs, $row_re) {
                        if (!is_array($value)) {

                            // dump('<<-------------', $this->getLetters($row_rr), $value, '------------->>');

                            $sheet->cell($this->getLetters($row_rr) . $row_rs, function ($cell) use ($value) {
                                $cell->setValue($value);
                                $cell->setAlignment('center');
                                $cell->setValignment('center');
                            });

                            $row_rr ++;
                        } else {
                            $row_rr += (is_array($value[0]) ? count($value[0]) : $value[0]);
                        }
                    });

                });

                $sheet->prependRow(1, [$model::translationTitle('export')]);
                $sheet->mergeCells('A1:' . $this->getLetters($sheet_header_c-1) . '1');
                $sheet->setHeight([
                    1 => 50,
                    2 => $height_global,
                    3 => $height_global
                ]);

                $sheet->cell('A1', function ($cell) {

                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBackground('#c1c1c1');
                    $cell->setFont([
                        'size'       => '18',
                        'bold'       =>  true
                    ]);
                });

            });

        })->export('xlsx');

        exit;
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
     * @param $row
     * @param string $fd
     * @param string $quot
     * @return string
     */
    protected static function putcsv($row, $fd = ',', $quot = '"')
    {
        $str = '';
        foreach ($row as $cell) {
            $cell = str_replace([$quot, "\n"], [$quot . $quot, ''], $cell);
            if (strchr($cell, $fd) !== FALSE || strchr($cell, $quot) !== FALSE) {
                $str .= $quot . $cell . $quot . $fd;
            } else {
                $str .= $cell . $fd;
            }
        }
        return substr($str, 0, -1) . "\n";
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

    /**
     * Todo: Wait to develop
     */
    public function __render()
    {
        $model = $this->model;
        $sheet_headers = $model::getSheetHeader('export');

        Admin::script(
            <<<EOT

$(".export-form").on('submit', function () {
    alert();
    return false;
});

EOT
        );

        return
            view('admin.exporter.modal')
                ->with([
                    'export_url' => $this->grid()->exportUrl(),
                    'sheet_headers' => $sheet_headers
                ])
                ->render();
    }
}