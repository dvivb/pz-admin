<?php

namespace App\Admin\Api;

use App\Dictionary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DictionaryApi extends Controller
{
    public static function dictionaries($subject, $is_deep=0, Request $request)
    {
        $q = $request->get('q');

        return
            Dictionary
                ::when(true, function ($query) use ($is_deep, $q) {
                    if ($is_deep) {
                        return $query->where('parent_id', $q['loader_id']);
                    } else {
                        return $query->where('parent_id', 0);
                    }
                })
                ->where('subject', $subject)
                ->when(isset($q['keywords']), function ($query) use ($q) {
                    return $query->where('name', 'like', "%$q%");
                })
                ->paginate(null, ['id', 'name as text']);
    }
}
