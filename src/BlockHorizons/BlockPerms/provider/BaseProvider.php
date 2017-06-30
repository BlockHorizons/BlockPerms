<?php

namespace BlockHorizons\BlockPerms\provider;

use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\permission\Permission;

use BlockHorizons\BlockPerms\Loader;
use BlockHorizons\BlockPerms\entity\BPGroup;
use BlockHorizons\BlockPerms\entity\BPUser;

abstract class BaseProvider {
    /** @var Loader */
    protected $loader;
    
    /** @var array */
    protected $groups = [];
    protected $players = [];
    
    /** @var Config */
    private $groupData;
    
    public function __construct(Loader $loader) {
        $this->loader = $loader;
        // STFU!
        @mkdir($loader->getDataFolder() . "groups");
        @mkdir($loader->getDataFolder() . "players");
    }

    public abstract function load();
    
    public abstract function addGroup(BPGroup $group, array $data);
    
    protected function addGroupImpl(BPGroup $group, array $data, string $extension, $configType) {
        $this->groupData = new Config($this->getLoader()->getDataFolder() . "groups/" . $group->getName() . ".$extension", $configType);
        $this->groupData->setAll($data);
        $this->groupData->save();
        $this->groups[$group->getName()] = $group;
    }
    
    public abstract function addPlayer(Player $player);
    
    protected function addPlayerImpl(Player $player, array $data, string $extension, $configType) {
        $this->playerData = new Config($this->getLoader()->getDataFolder() . "players/" . $player->getUniqueId() . ".$extension", $configType);
        $this->playerData->setAll($data);
        $this->playerData->save();  
        $this->registerPlayerImpl(new BPUser($data), $data);
    }
    
    protected function registerPlayerImpl(BPUser $user, array $data) {
        $this->players[$user->getName()] = $user;
    }
    
    public function initPermissions() {
        $count = 0;
        foreach($this->groups as $group) {
            foreach($group->getPermissions() as $permission) {
                 $perm = $this->getLoader()->getServer()->getPluginManager()->getPermission($permission);
                 if(!$perm instanceof Permission) {
                     $count++;
                     if($count <= 5) {
                         $this->getLoader()->warn($this->plugin->translate("console.permission.not-found", [
                             $permission, 
                             $group->getName()
                         ]));
                     }
                 }
            }
        }
        if($count >= 5) {
            $this->getLoader()->log($this->plugin->translate("console.permission.count-not-found", [$count]));
        }
    }
    
    public function getGroup(string $name) {
        foreach($this->groups as $group) {
            if($group->getName() === $name) {
                return $group;
            }
        }
        return null;
    }
    
    public function getUser(string $name) {
        foreach($this->players as $user) {
            if($user->getName() === $name) {
                return $user;
            }
        }
    }
    
    public function getGroups(): array {
        return $this->groups;
    }
    
    protected function getLoader(): Loader {
		return $this->loader;
    }
    
    public abstract function getName(): string;
}
