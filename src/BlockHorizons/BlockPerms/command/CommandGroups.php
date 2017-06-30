<?php

namespace BlockHorizons\BlockPerms\command;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as TF;

use BlockHorizons\BlockPerms\Loader;
use BlockHorizons\BlockPerms\util\TextUtil;

class CommandGroups extends ICommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "groups");
	}
    
	public function perform(CommandSender $sender, array $args) {
		$sender->sendMessage(TextUtil::titleize($this->getLoader()->translate("commands.groups.header")));
		$groups = $this->getLoader()->getGroups();
        
		if(empty($groups)) {
			$sender->sendMessage($this->getLoader()->translate("commands.groups.none"));
			return;
		}
		foreach($groups as $group) {
			$sender->sendMessage($this->getLoader()->translate("commands.groups.success", [$group->getDisplayName()]));
		}
	}
}
