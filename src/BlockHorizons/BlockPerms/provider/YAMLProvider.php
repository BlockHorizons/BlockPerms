<?php

namespace BlockHorizons\BlockPerms\provider;

use pocketmine\Player;
use pocketmine\utils\Config;

use BlockHorizons\BlockPerms\Loader;
use BlockHorizons\BlockPerms\entity\BPGroup;
use BlockHorizons\BlockPerms\entity\BPUser;

class YAMLProvider extends BaseProvider {

    public function __construct(Loader $loader) {
        parent::__construct($loader);
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
        parent::addGroupImpl($group, $data, "yaml", Config::YAML);
    }
    
    public function addPlayer(Player $player) {
        parent::addPlayerImpl($player, [
            "username" => $player->getName(),
            "groups" => [],
            "permissions" => []
        ], "yaml", Config::YAML);
    }
    
    public function registerPlayer(Player $player) {
        $data = yaml_parse_file($this->getLoader()->getDataFolder() . "players/" . $player->getUniqueId() . ".yaml");
        parent::registerPlayerImpl(new BPUser($data), $data);
    }
    
    public function getName(): string {
		return "yaml";
	}
}
