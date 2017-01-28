<?php

namespace RTG\AntiFly\cmd;

use RTG\AntiFly\Loader;

use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\command\Command;

use pocketmine\utils\Config;

/**
 * Description of AntiCommand
 *
 * @author RTG
 */

class AntiCommand implements CommandExecutor {
    
    public $plugin;
    
    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $param) {
        switch(strtolower($cmd->getName())) {
            
            case "anticheat":
                if($sender->hasPermission("anticheat.command")) {
                    
                    if(isset($param[0])) {
                        switch(strtolower($param[0])) {
                            
                            case "add":
                                
                                if(isset($param[1])) {
                                    
                                    $n = $param[1];
                                    
                                            if(isset($this->plugin->whitelist[strtolower($n)])) {
                                                $sender->sendMessage("[Error] This username is already in the Whitelist :)");
                                                return false; 
                                            }
                                            else {
                                                $this->plugin->whitelist[strtolower($n)] = $n;
                                                $sender->sendMessage("[AntiCheat] You have added $n to the Cheat whitelist :)");
                                            }
                                            
                                            $this->plugin->saveEm();
  
                                }
                                else {
                                    $sender->sendMessage("Usage: /anticheat add [username]");
                                }
                                
                                return true;
                            break;
                            
                            case "rm":
                                
                                if(isset($param[1])) {
                                    
                                    $n = $param[1];
                                    
                                        if(isset($this->plugin->whitelist[strtolower($n)])) {
                                            unset($this->plugin->whitelist[strtolower($n)]);
                                            $sender->sendMessage("[AntiCheat] You have removed $n from the Cheat whitelist!");   
                                        }
                                        else {
                                            $sender->sendMessage("[Error] This username isn't even in the list :(");
                                        }
                                        
                                        $this->plugin->saveEm();
                                      
                                }
                                else {
                                    $sender->sendMessage("Usage: /anticheat rm [username]");
                                }
                                
                                return true;
                            break;
                            
                            case "help":
                                
                                $sender->sendMessage("[AntiCheat] /anticheat < add | rm | list >");
                                
                                return true;
                            break;
                            
                            case "list":
                                
                                $list = new Config($this->plugin->getDataFolder() . "whitelist.txt", Config::ENUM);
                                
                                $msg = $list->getAll(true);
                                
                                $m = implode(", ", $msg);
                                
                                $sender->sendMessage("-- Your Cheat Execption List --");
                                $sender->sendMessage($m);
                                 
                                return true;
                            break;
                            
                            case "test": // For Testing purpose !
                                
                                $this->plugin->set($sender, 0);
                                
                                return true;
                            break;
                               
                        }
                            
                    }
                    else {
                        $sender->sendMessage("Usage: /anticheat help");
                    }
                                   
                }
                
                return true;
            break;
               
        }
        
    }
    
}