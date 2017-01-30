<?php

namespace RTG\AntiCheat;

use RTG\AntiCheat\cmd\AntiCommand;

/* Essentials */
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\level\Level;
use pocketmine\block\Block;

use pocketmine\utils\Config;

/* Executor */
use pocketmine\command\CommandExecutor;

/* Events */
use pocketmine\event\player\PlayerMoveEvent;

class Loader extends PluginBase implements Listener {
    
    public $whitelist;
    
    public function onEnable() {
       @mkdir($this->getDataFolder());
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->whitelist = array();
        
        /* Execution */
        $this->getCommand("anticheat")->setExecutor(new AntiCommand ($this));
        $list = new Config($this->getDataFolder() . "whitelist.txt", Config::ENUM);
        $this->points = new Config($this->getDataFolder() . "points.yml", Config::YAML, array());
        
        $this->whitelist = $list->getAll();;
    }
    
    public function saveEm() {
        $list = new Config($this->getDataFolder() . "whitelist.txt", Config::ENUM);
        $list->setAll($this->whitelist);
        $list->save();
    }
    
    /* Event */
    
    public function onMove(PlayerMoveEvent $e) {
        /* Gather Info */
        $p = $e->getPlayer();
        $n = $p->getName();
        $block = $p->getLevel()->getBlock($p->subtract(0, 1, 0));
        
            if($block->getID === 0 and !$block->getID() == 10 and !$block->getID() == 11 and !$block->getID() == 8 and !$block->getID() == 9 and !$block->getID() == 182 and !$block->getID() == 126 and !$block->getID() == 44) {
                if(isset($this->whitelist[strtolower($n)])) {
                    return false;
                }
                else {
                    
                    if(!$this->points->exists($p->getName())) {
                        $this->points->set($p->getName(), 0);
                    }
                    
                    else {
                        $this->set($p, ($this->get($p)+1));
                        //todo add config auto ban = bad
                        if($this->get($p) === 2) {
                                $p->kick("You have been kicked for Suspicious Activity");
                        }
                        else if($this->get($p) === 4) {
                            $p->kick("Please refrain from Hacking or moving incorrectly!");   
                        }
                        elseif ($this->get($p) === 6) {
                            $this->points->set($p->getName(), 0);
                            $p->setBanned(true); 
                            $this->getServer()->broadcastMessage("[LEET] $n has been banned due to Suspicious Activity!");
                            }
                                           
                    }
                      
                }
                    
            }
            
    }
    
    public function get(Player $player) {
        return $this->points->get($player->getName());
    }
    
    public function set(Player $player,$v){
        $this->points->set($player->getName(),$v);
        $this->points->save();
        return true;
    }
    
    public function onDisable() {
        $this->saveEm();
    }

}
