<?php
namespace Forter;

use Forter\provider\ProviderMain;
use Forter\command\SendBar;

use pocketmine\{ plugin\PluginBase, inventory\Inventory, item\Item, command\CommandSender, command\Command, Player, scheduler\CallbackTask, utils\Config, level\Level };

class Main extends PluginBase{
    public $hotbar = [];
  
    private Config $config;
    private ProviderMain $provider;
  
    public function onEnable() : void{
  	$this->load();
      $this->registerCommands();
      $this->loadProvider();
   }    
  
    public function load() : void{
       $folder = $this->getDataFolder();
       
       if(!is_dir($folder)){
			mkdir($folder);
		}
		$this->saveDefaultConfig();
		$file = $folder."config.yml";
	    $this->config = new Config($file);    
	
	   $update = $this->config->get("updata");
       $this->getServer()->getScheduler()->scheduleRepeatingTask(new CallbackTask(array($this, "Task")), $update);
    }
    
    private function loadProvider() : void{
    	$this->provider = new ProviderMain($this);
    }
    
    private function registerCommands() : void{
        $commandMap = $this->getServer()->getCommandMap();
        $commandMap->register('bar', new SendBar($this, 'bar', ' Включить/Отключить бар'));
    }
  
    public function getSettingBar(Player $player) : string{
    	$data = str_replace([
        "{ServerName}",
        "{nick}",
        "{data}",
        "{item}",
        "{money}",
        "{group}",
        "{x}",
        "{y}",
        "{z}",
        "{color}",
        "{right}",
        "{online}",
        "{maxOnline}",
        "{IDitem}",
        "{DAMAGitem}",
        "{clan}",
        "{tps}",
        "{ping}",
        "{near}"
        ], 
        [
        $this->config->get("ServerName"),
        $player->getName(),
        $this->provider->getDateFormat(),
        $player->getInventory()->getItemInHand()->getName(),
        $this->provider->getEcoMoney($player),
        $this->provider->getPurePerms($player),
        $player->getFloorX(),
        $player->getFloorY(),
        $player->getFloorZ(),
        $this->provider->getColorsRandom(),
        str_repeat(" ", 80),
        count($this->getServer()->getOnlinePlayers()),
        $this->getServer()->getMaxPlayers(),
        $player->getInventory()->getItemInHand()->getId(),
        $player->getInventory()->getItemInHand()->getDamage(),
        $this->provider->getFaction($player), 
        $this->getServer()->getTicksPerSecond(),
        $player->getPing(),
        $this->provider->getNear($player)
        ], $this->config->get("HotBar"));
        $text = implode("\n", $data);
        return $text;
    }
  
    public function getFormatSend(Player $player, string $format) : void{
    	if($format == 'Tip'){    	 
  	     $player->sendTip($this->getSettingBar($player).str_repeat("\n", 11));
        }elseif($format == 'Popup'){
       	$player->sendPopup($this->getSettingBar($player));
        }
    }
  
    public function Task(){
        foreach($this->getServer()->getOnlinePlayers() as $player){
            if(!isset($this->hotbar[$player->getName()])){
            	$world = $player->getLevel()->getName();
                if(!in_array($world, $this->config->get('Default-world'))){
                	return false;
                }
                $this->getFormatSend($player, $this->config->get('Format-Send'));
            }
        }
    }
}