<?php

namespace App\Admin\Api;

use App\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectApi extends Controller
{
    public static function projects(Request $request)
    {
        $q = $request->get('q');

        return
            Project
                ::when(isset($q['keywords']), function ($query) use ($q) {
                    return $query->where('name', 'like', '%' . $q['keywords'] . '%');
                })
                ->paginate(null, ['id', 'name as text']);
    }
}
