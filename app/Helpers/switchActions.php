<?php

namespace App\Helpers;

trait switchActions
{
    public function switchAction($action, $update = false, $resets = [], $session = [])
    {
        $this->action = $action;
        $this->update = $update;
        if (is_array($resets) && count($resets) > 0) {
            $this->reset($resets);
        }
        if (is_array($session) && count($session) > 0) {
            session()->forget($session);
        }
    }
}
