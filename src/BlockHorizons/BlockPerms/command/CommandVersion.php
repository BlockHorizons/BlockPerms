<?php

namespace BlockHorizons\BlockPerms\command;

use pocketmine\command\CommandSender;

use BlockHorizons\BlockPerms\Loader;
use BlockHorizons\BlockPerms\util\TextUtil;

class CommandVersion extends ICommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "version");
	}
    
	public function perform(CommandSender $sender, array $args) {
		$sender->sendMessage(TextUtil::titleize($this->getLoader()->translate("commands.version.header")));
		$sender->sendMessage($this->getLoader()->translate("commands.version.name", [$this->getLoader()->getDescription()->getName()]));
		$sender->sendMessage($this->getLoader()->translate("commands.version.version", [$this->getLoader()->getDescription()->getVersion()]));
	}
}
