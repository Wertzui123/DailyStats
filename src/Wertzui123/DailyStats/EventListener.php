<?php

namespace Wertzui123\DailyStats;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class EventListener implements Listener
{

    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        if (!$event->getPlayer()->hasPlayedBefore()) $this->plugin->addRegisteredPlayer();
        $this->plugin->addJoinedPlayer($event->getPlayer());
    }

}