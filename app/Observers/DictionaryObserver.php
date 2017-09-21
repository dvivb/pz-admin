<?php

namespace App\Observers;

use App\Dictionary;

class DictionaryObserver
{

    /**
     * 监听用户新增的事件
     *
     * @param Dictionary $dictionary
     */
    public function creating(Dictionary $dictionary)
    {
        $this->saving($dictionary);
    }

    /**
     * 监听用户保存的事件
     *
     * @param Dictionary $dictionary
     */
    public function updating(Dictionary $dictionary)
    {
        $this->saving($dictionary);
    }

    /**
     * 监听用户保存的事件
     *
     * @param  Dictionary  $dictionary
     * @return void
     */
    public function saving(Dictionary $dictionary)
    {
        $parent_id = $dictionary->parent_id ? : 0;
        $dictionary->setAttribute('parent_id', $parent_id);
    }
}
