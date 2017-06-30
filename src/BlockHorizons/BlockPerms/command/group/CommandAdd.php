<?php

namespace BlockHorizons\BlockPerms\command\group;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as TF;

use BlockHorizons\BlockPerms\command\ICommand;
use BlockHorizons\BlockPerms\Loader;
use BlockHorizons\BlockPerms\entity\BPGroup;
use BlockHorizons\BlockPerms\util\TextUtil;

class CommandAdd extends ICommand {

    public function __construct(Loader $loader) {
        parent::__construct($loader, "add");
        $this->description = $loader->translate("commands.group.add.description");
    }
    
    public function perform(CommandSender $sender, array $args) {
        if(!$sender->hasPermission("bp.command.group.add")) {
            $sender->sendMessage($this->getLoader()->translate("commands.no-permission"));
            return;
        }
        if(count($args) <= 1) {
            $sender->sendMessage($this->getLoader()->translate("commands.group.add.noargs", [$this->getUsage()]));
            return;
        }
        
        $group = new BPGroup([
            "name" => strtolower($args[1]),
            "displayName" => $args[1],
            "permissions" => []
        ]);      
        $group->create();
        
        $sender->sendMessage($this->getLoader()->translate("commands.group.add.success", [$args[1]]));
    }
    
    // todo cleanup
    public function getUsage(): string {
        return TF::AQUA . "/pp " . TF::DARK_AQUA . "group add " . TF::GRAY . "<group>";
    }
}
