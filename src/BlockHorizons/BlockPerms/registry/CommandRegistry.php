<?php

namespace BlockHorizons\BlockPerms\registry;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;

use BlockHorizons\BlockPerms\Loader;
use BlockHorizons\BlockPerms\command\ICommand;

class CommandRegistry extends PluginCommand {
    private $commands = [];
    
    public function __construct(Loader $loader) {
        parent::__construct("blockperms", $loader);
        $this->setAliases(["bp", "perms", "pp"]);
        
        $this->registerAll($loader);
    }
    
    public function execute(CommandSender $sender, $label, array $args) {
        if(count($args) > 0) {
            $subCommand = strtolower(array_shift($args));
            if(isset($this->commands[$subCommand])) {
                $command = $this->commands[$subCommand];
            } else {
                $sender->sendMessage($this->loader->translate("commands.not-found", [$subCommand]));
                return true;
            }
            $command->perform($sender, $args);
        }
    }
    
    public function getCommand(string $name) {
        return $this->commands[$name];
    }
    
    public function getCommands(): array {
        return $this->commands;
    }
    
    private function registerAll(Loader $loader) {
        $commands = [
            "version" => "\\BlockHorizons\\BlockPerms\\command\\CommandVersion",
            "group" => "\\BlockHorizons\\BlockPerms\\command\\CommandGroup",
            "groups"=> "\\BlockHorizons\\BlockPerms\\command\\CommandGroups"
        ]; 
        
        foreach($commands as $command => $class) {
            $command = new $class($loader);
            $this->commands[$command->getName()] = $command;
        }
        
        // Initialize other sub commands
        ($this->getCommand("group"))->init();
    }
}
