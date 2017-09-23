<?php

namespace App\Admin\Api;

use App\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MemberApi extends Controller
{
    public  function member(Request $request)
    {
//        return 1;
//        var_dump($_GET);
        var_dump($request->get('email'));
        exit;
//        $q = $request->get('q');
//
//        return
//            Project
//                ::when(isset($q['keywords']), function ($query) use ($q) {
//                    return $query->where('name', 'like', '%' . $q['keywords'] . '%');
//                })
//                ->paginate(null, ['id', 'name as text']);
    }
}
