<?php

namespace Wertzui123\DailyStats;

use pocketmine\scheduler\Task;

class CheckForDayChangeTask extends Task
{

    private $plugin;
    private $lastNotify = 0;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onRun(int $currentTick)
    {
        if (time() > ($this->lastNotify + 10) && date('H') === '00' && date('i') === '00' && (date('s') < 10)) {
            $this->lastNotify = time();
            $this->plugin->notify();
        }
    }

}