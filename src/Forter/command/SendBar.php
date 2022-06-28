<?php

namespace Forter\command;

use Forter\Main;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;

class SendBar extends Command implements PluginIdentifiableCommand{
	
	private Main $plugin;
	
	public function __construct(Main $plugin, $name, $description){
        $this->plugin = $plugin;
        parent::__construct($name, $description);        
    }
    
    public function execute(CommandSender $sender, $label, array $args){
        if(isset($this->plugin->hotbar[$sender->getName()])){
				unset($this->plugin->hotbar[$sender->getName()]);
				$sender->sendMessage("§7Хот Бар §aуспешно§7 включен!");
		}else{
				$this->plugin->hotbar[$sender->getName()] = true;
				$sender->sendMessage("§7Хот Бар §cуспешно§7 отключен");
		}
    }
    
    public function getPlugin(){
    	return $this->plugin;
    }
}