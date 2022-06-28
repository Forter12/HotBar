<?php

namespace Forter\provider;

use Forter\Main;

use pocketmine\{ utils\Config, Player };

class ProviderMain{
	
	private Config $config;
	private Main $plugin;
	public $distance = array();
	
	public function __construct(Main $plugin){
		$this->plugin = $plugin;
		$file = $plugin->getDataFolder().'config.yml';
		$this->config = new Config($file);
	}
	
	public function getFaction(Player $player) : string {
    $name = strtolower($player->getName());
		if($this->getFractionsPro() !== null){
		 	 $clan = $this->getFractionsPro()->getPlayerFaction($name);
			return $clan !== null ? $clan : $this->config->get('Default-faction');
		}
		return 'Нет плагина';
	}
	
	public function getDateFormat() : string{
         switch($this->config->get('monthFormat', 'число')){
           case 'число':
             $month = 'm';
           break;
           case 'месяц':
             $month = 'F';
           break;
        }
        $format = str_replace(['{год}', '{месяц}', '{день}', '{час}', '{минут}', '{секунд}'], ['Y', 'm', 'd', 'H', 'i', 's'], $this->config->get('FormatData'));     
        $data = date($format);
        if($this->config->get('monthFormat') == 'месяц') $data = str_replace(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'], ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'], $data); 
        return $data;
	}
	
	public function getNear(string $iplayer) : string{
		$players = $this->plugin->getServer()->getOnlinePlayers();
		if(count($players) <= 1){
			return 'единственный';
		}
		
		foreach($players as $player){
			if($player->getName() != $iplayer->getName()){
				if($sender->distance($player) <= $this->config->get('distance-near')){
					$this->distance = $player;
				}
			}
		}
		
		if(count($distance) <= 0){
			return 'Не обнаружено';
		}
		
		foreach($this->distance as $near){
			return round($sender->distance($near)).' блоков';
		}
	}
	
	public function getColorsRandom() : string{
		$colors = ['§1', '§2', '§3', '§4', '§5', '§6', '§7', '§8', '§9', '§a', '§b', '§c', '§d', '§e', '§f'];
        $color = $colors[array_rand($colors)];
        return $color;
	}
	
	public function getEcoMoney(string $player) : string{
		 $economy = $this->plugin->getServer()->getPluginManager()->getPlugin('EconomyAPI');
     	return $economy !== null ? $economy->myMoney($player) : 'Нет плагина';
     }
     
     public function getPurePerms(string $player) : string {
     	$permission = $this->plugin->getServer()->getPluginManager()->getPlugin('PurePerms');
     	return $permission !== null ? $permission->getUserDataMgr()->getData($player)['group'] : 'Нет плагина';
     }
     
     public function getFractionsPro(){
     	$fractions = $this->plugin->getServer()->getPluginManager()->getPlugin('FractionsPro');
     	return $fractions;
     }
}