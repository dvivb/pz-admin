<?php

namespace App\Observers;

use App\Period;
use Illuminate\Support\Facades\Input;

class PeriodObserver
{

    /**
     * 监听期数的新增事件
     *
     * @param  Period  $period
     * @return void
     */
    public function saving(Period $period)
    {
        if ($period->project) {
            $period->project->period = $period->period;
            $period->project->save();
        }
    }

    /**
     * 监听期数的删除事件
     *
     * @param  Period  $period
     * @return void
     */
    public function deleting(Period $period)
    {
        $period_max = Period
            ::where('project_id', $period->project_id)
            ->pluck('period', 'id')
            ->except($period->id)
            ->max();

        // 如果删除的期数 不是最大的 期数
        if ($period->period < $period_max) {
            Input::session()->put('del_osb_error', '请删除最大的期数');

            return false;
        }

        // 如果删除的期数 下面有房屋征收记录
        if ($period->houseLevy()->count()) {
            Input::session()->put('del_osb_error', '请先删除该期数下的所有征收记录');

            return false;
        }

        if ($period->project) {
            $period->project->period = $period->period - 1;
            $period->project->save();
        }
    }
}
