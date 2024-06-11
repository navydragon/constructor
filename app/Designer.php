<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Designer extends Model
{
    public function changePositionBelow()
    {
        $below = Designer::where('dpp_id',$this->dpp_id)->where('position','>',$this->position)->get();
        foreach ($below as $item)
        {
            $item->position = $item->position - 1;
            $item->save();
        }
        return "OK";
    }
}
