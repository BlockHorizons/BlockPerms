<?php

namespace BlockHorizons\BlockPerms\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

use BlockHorizons\BlockPerms\Loader;

class PlayerListener implements Listener {
	/** @var Loader */
	private $loader;

	public function __construct(Loader $loader) {
		$this->loader = $loader;
	}
    
	public function onPlayerJoin(PlayerJoinEvent $ev) {
		$player = $ev->getPlayer();
		$provider = $this->getLoader()->getProvider();
        
		if($this->getLoader()->getUser($player->getName()) === null) {
			$provider->addPlayer($player);    
		} else {
			$provider->registerPlayer($player);
		}
	}
        
	private function getLoader(): Loader {
		return $this->loader;
	}
}
