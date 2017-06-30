<?php

namespace BlockHorizons\BlockPerms\provider;

use pocketmine\utils\Config;
use pocketmine\Player;
use pocketmine\IPlayer;

use BlockHorizons\BlockPerms\Loader;
use BlockHorizons\BlockPerms\entity\BPGroup;
use BlockHorizons\BlockPerms\entity\BPUser;

class JSONProvider extends BaseProvider {
  
    public function __construct(Loader $loader) {
        parent:__construct($loader);
    }

    public function load() {
        // Groups
        $directory = $this->getLoader()->getDataFolder() . "groups/";
        foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory)) as $file => $groups) {
            if(pathinfo($file)["extension"] === "yaml") {
                $data = yaml_parse_file($groups);
                $group = new BPGroup($data);
                $this->groups[$group->getName()] = $group;
            }
        }    
        // Players
        $directory = $this->getLoader()->getDataFolder() . "players/";
        foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory)) as $file => $players) {
            if(pathinfo($file)["extension"] === "yaml") {
                $data = yaml_parse_file($players);
                $player = new BPUser($data);
                $this->players[$player->getName()] = $player;
            }
        }    
        // Initialize permissions
        $this->initPermissions();
    }
    
    public function addGroup(BPGroup $group, array $data) {
        parent::addGroupImpl($group, $data, "json", Config::JSON);
    }
    
    public function addPlayer(Player $player, array $data) {
        $this->playerData = new Config($this->getLoader()->getDataFolder() . "players/" . $player->getUniqueId() . $this->extension, $this->configType);
        $this->playerData->setAll([
            "username" => $player->getName(),
            "groups" => [
                "default"
            ],
            "permissions" => []
        ]);
        $this->playerData->save();
    }
    
    public function registerPlayer(Player $player) {
        $data = yaml_parse_file($this->getLoader()->getDataFolder() . $player->getUniqueId() . ".yaml");
        parent::registerPlayerImpl(new BPUser($data), $data);
    }
    
    public function setGroup(IPlayer $player, BPGroup $group) {
    
    }
    
    public function getName(): string {
        return "json";
    }
}
