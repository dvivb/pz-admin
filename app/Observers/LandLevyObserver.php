<?php

namespace App\Observers;

use App\LandLevy;

class LandLevyObserver
{

    /**
     * 监听土地征收的删除事件
     *
     * @param  LandLevy  $dictionary
     * @return void
     */
    public function deleting(LandLevy $land_levy)
    {
        $land_levy->landLevyDicSnap()->delete();
    }
}
