<?php

namespace BlockHorizons\BlockPerms\command;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as TF;

use BlockHorizons\BlockPerms\Loader;
use BlockHorizons\BlockPerms\util\TextUtil;

class CommandGroup extends ICommand {
    private $commands = [];

    public function __construct(Loader $loader) {
        parent::__construct($loader, "group");
    }
    
    public function init() {
        $commands = [
            "add" => "\\BlockHorizons\\BlockPerms\\command\\group\\CommandAdd"
        ];
        
        foreach($commands as $command => $class) {
            $command = new $class($this->getLoader());
            $this->commands[$command->getName()] = $command;
        }
    }
    
    public function perform(CommandSender $sender, array $args) {
        if(count($args) > 0) {
            if(isset($this->commands[$args[0]])) {
                ($this->commands[$args[0]])->perform($sender, $args);
            }
        } else {
            $sender->sendMessage(TextUtil::titleize($this->getLoader()->translate("commands.group.header")));
            foreach($this->commands as $command) {
                $sender->sendMessage($command->getUsage());
            }
        }
    }
}
