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
        $today = $this->plugin->getTime();
        if (time() > ($this->lastNotify + 10) && $today->format('H') === '00' && $today->format('i') === '00' && ($today->format('s') < 10)) {
            $this->lastNotify = time();
            $this->plugin->notify();
        }
    }

}