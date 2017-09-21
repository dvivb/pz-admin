<?php

namespace App\Observers;

use App\HouseLevy;

class HouseLevyObserver
{

    /**
     * 监听房屋征收的删除事件
     *
     * @param  HouseLevy  $dictionary
     * @return void
     */
    public function deleting(HouseLevy $house_levy)
    {
        $house_levy->unsummarizing(); // 回滚汇总
        $house_levy->houseLevyDicSnap()->delete(); // 解除 房屋征收字典表快照 的关联
    }
}
