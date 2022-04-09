<?php

namespace Wertzui123\DailyStats;

use pocketmine\scheduler\Task;

class CheckForDayChangeTask extends Task
{

    /** @var Main */
    private $plugin;
    /** @var int */
    private $lastNotify = 0;

    /**
     * CheckForDayChangeTask constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onRun(): void
    {
        $today = $this->plugin->getTime();
        if (time() > ($this->lastNotify + 10) && $today->format('H') === '00' && $today->format('i') === '00' && ($today->format('s') < 10)) {
            $this->lastNotify = time();
            $this->plugin->notify();
        }
    }

}