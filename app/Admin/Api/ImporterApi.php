<?php

namespace App\Admin\Api;

use App\Admin\Extensions\ImportTmplExporter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ReflectionClass;

/**
 * Class ImporterApi
 *
 * @package App\Admin\Api
 */

class ImporterApi extends Controller
{
    protected $target;
    protected $model;

    /**
     * 导入数据
     * @param $target
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import($target, Request $request)
    {
        $this->setModel($target);

        $this->validate($request, [
            'ul_fl' => 'required|file|mimetypes:application/vnd.ms-office,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/octet-stream',
        ]);

        $path = $request->file('ul_fl')->getPathname();
        // $class_name = 'App\\Admin\\Extensions\\Importer\\' . ucfirst(camelize($target)) . 'Importer';
        $class_name = 'App\\Admin\\Extensions\\Importer';

        $reflection = new ReflectionClass($class_name);
        $importer = $reflection->newInstanceArgs([$this->model]);
        $importer->importFace($path);

        admin_toastr(trans('admin.import_succeeded'));
        return back();
    }

    /**
     * 拉取导入模板
     * @param $target
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function pullTemplate($target)
    {
        $this->setModel($target);
        $model = $this->model;

        $file_prefix = '.xlsx';

        // 如果导入模板文件存在 下载
        if (file_exists(app()->basePath() . '/storage/app/import_template/' . $target . $file_prefix)) {
            return response()->download(
                app()->basePath() . '/storage/app/import_template/' . $target . $file_prefix,
                $model::translationTitle('import_tpl') . $file_prefix
            );
        }

        // 导出 导入模板
        $import_tmpl_exporter = new ImportTmplExporter($this->model);
        $import_tmpl_exporter->export();
    }

    /**
     * 设置模型类名称
     */
    public function setModel($target = '')
    {
        $target = $target ? : $this->target;
        $class_name = 'App\\' . ucfirst(camelize($target));
        if (class_exists($class_name)) {
            $this->model = $class_name;
        }

        return $this;
    }
}
