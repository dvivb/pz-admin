<?php

use Illuminate\Routing\Router;

Admin::registerHelpersRoutes();

Route::group([
    'prefix'        => config('admin.prefix'),
    'namespace'     => Admin::controllerNamespace(),
    'middleware'    => ['web', 'admin'],
], function (Router $router) {
    $router->get('/', 'HomeController@index');

    $router->resource('house_levies', HouseLevyController::class);
    $router->resource('transitions', TransitionController::class);
    $router->resource('land_levies', LandLevyController::class);
    $router->resource('projects', ProjectController::class);
    $router->resource('placements', PlacementController::class);
    $router->resource('house_structures', Dictionary\HouseStructureController::class);
    $router->resource('annexe_structures', Dictionary\AnnexeStructureController::class);
    $router->resource('attaches', Dictionary\AttachController::class);
    $router->resource('structures', Dictionary\StructureController::class);
    $router->resource('equipments', Dictionary\EquipmentController::class);
    $router->resource('land_status', Dictionary\LandStatusController::class);
    $router->resource('young_crops', Dictionary\YoungCropController::class);

    $router->resource('member', MemberController::class);

    $router->resource('periods', PeriodController::class, ['except' => ['index']]);

    $router->get('api/projects', '\App\Admin\Api\ProjectApi@projects');
    $router->get('api/dictionaries/{subject}', '\App\Admin\Api\DictionaryApi@dictionaries');
    $router->get('api/dictionaries/{subject}/{is_deep}', '\App\Admin\Api\DictionaryApi@dictionaries');

    // 导入文件
    $router->post('api/import/{target}', '\App\Admin\Api\ImporterApi@import');
    $router->get('api/import_templates/{target}', '\App\Admin\Api\ImporterApi@pullTemplate');
});
