<?php

namespace RTG\AntiFly;

use RTG\AntiFly\cmd\AntiCommand;

/* Essentials */
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Server;
use pocketmine\Player;

use pocketmine\utils\Config;

/* Executor */
use pocketmine\command\CommandExecutor;

/* Events */


class Loader extends PluginBase implements Listener {
    
    public $whitelist;
    
    public function onEnable() {
       @mkdir($this->getDataFolder());
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->whitelist = array();
        
        /* Execution */
        $this->getCommand("anticheat")->setExecutor(new AntiCommand ($this));
        $list = new Config($this->getDataFolder() . "whitelist.txt", Config::ENUM);
        
        $this->whitelist = $list->getAll();
    }
    
    public function saveEm() {
        $list = new Config($this->getDataFolder() . "whitelist.txt", Config::ENUM);
        $list->setAll($this->whitelist);
        $list->save();
    }
    
    public function onDisable() {
        $this->saveEm();
    }

}