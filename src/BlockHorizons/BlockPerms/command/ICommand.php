<?php

namespace BlockHorizons\BlockPerms\command;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as TF;

use BlockHorizons\BlockPerms\Loader;

abstract class ICommand {
	/** @var Loader */
    public $loader;
    
    private $name;
    private $description;
    
    public $arguments;

    public function __construct(Loader $loader, string $name) {
        $this->loader = $loader;
        $this->name = $name;
        $this->description = $loader->translate("commands.$name.description") ?? "";
    }
    
    public function getName(): string {
        return $this->name;
    }
    
    public function getArguments(): string {
        return $this->arguments ?? "";
    }
    
    public function getCommandRegistry() {
        return $this->getLoader()->getCommandRegistry();
    }
    
    protected function getLoader(): Loader {
    	return $this->loader;
    }
    
    public abstract function perform(CommandSender $sender, array $args);
}
