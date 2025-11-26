<?php

namespace App\Helpers;

trait switchActions
{
    public function switchAction($action, $update = false, $array = [])
    {
        $this->action = $action;
        $this->update = $update;
        if (is_array($array) && count($array) > 0) {
            $this->reset($array);
        }

    }
}
