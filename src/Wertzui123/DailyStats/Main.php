<?php

declare(strict_types=1);

namespace Wertzui123\DailyStats;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase
{

    /** @var Config */
    private $db;
    /** @var int */
    private $registeredPlayers;
    /** @var string[] */
    private $joinedPlayers;

    public function onEnable()
    {
        $this->saveDefaultConfig();
        $this->db = new Config($this->getDataFolder() . 'database.json');
        $this->registeredPlayers = $this->db->getNested(date('Y-m-d') . '.registered', 0);
        $this->joinedPlayers = $this->db->getNested(date('Y-m-d') . '.joined', []);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $task = new CheckForDayChangeTask($this);
        $handler = $this->getScheduler()->scheduleRepeatingTask($task, 100);
        $task->setHandler($handler);
    }

    /**
     * Increments the count of newly registered players in the database by one
     */
    public function addRegisteredPlayer()
    {
        $this->registeredPlayers++;
    }

    /**
     * Saves a players join to the database
     * @param Player $player
     */
    public function addJoinedPlayer(Player $player)
    {
        if (!in_array(strtolower($player->getName()), $this->joinedPlayers)) $this->joinedPlayers[] = strtolower($player->getName());
    }

    /**
     * Sends the summary of the day to the discord server
     */
    public function notify()
    {
        $text = str_replace(['{registered}', '{joined}'], $this->getConfig()->get('message'), [$this->registeredPlayers, count($this->joinedPlayers)]);
        $data = array('content' => $text, 'username' => $this->getConfig()->getNested('webhook.username'));
        $curl = curl_init($this->getConfig()->getNested('webhook.url'));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_exec($curl);
        $this->registeredPlayers = 0;
        $this->joinedPlayers = [];
    }

    public function onDisable()
    {
        $this->db->setNested(date('Y-m-d') . '.registered', $this->registeredPlayers);
        $this->db->setNested(date('Y-m-d') . '.joined', $this->joinedPlayers);
        $this->db->save();
    }

}