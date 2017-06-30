<?php

namespace BlockHorizons\BlockPerms;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;
use pocketmine\plugin\PluginBase;

use BlockHorizons\BlockPerms\registry\CommandRegistry;
use BlockHorizons\BlockPerms\listener\PlayerListener;
use BlockHorizons\BlockPerms\provider\BaseProvider;
use BlockHorizons\BlockPerms\provider\JSONProvider;
use BlockHorizons\BlockPerms\provider\YAMLProvider;

class Loader extends PluginBase {
	private $prefix = "§b[§aBlockPerms§b]§6 ";
    
	/** @var Config */
	private $language;
    
	/** @var CommandRegistry */
	private $commandRegistry;
    
	/** @var BaseProvider */
	private $provider;
    
	private static $instance;
    
	public static function getInstance() {
		return self::$instance;
	}
    
    public function onLoad() {
        self::$instance = $this;
    }

    public function onEnable() {
        $pluginManager = $this->getServer()->getPluginManager();
        if($pluginManager->getPlugin("PurePerms") !== null) {
            $this->log("PurePerms is enabled, please disable it before using this plugin.");
            $pluginManager->disablePlugin($this);
        }
        $this->saveDefaultConfig();
        Configuration::init($this->getConfig()->getAll());
        
        $this->language = new Config($this->getFile() . "resources/lang/" . Configuration::getLanguage() . ".json", Config::JSON);
        
        $this->commandRegistry = new CommandRegistry($this);
        $this->getServer()->getCommandMap()->register("blockperms", $this->commandRegistry);
        
        $this->getServer()->getPluginManager()->registerEvents(new PlayerListener($this), $this);
        
        $this->setProvider(Configuration::getProvider());
        $this->provider->load();
    }
    
    public function getCommandRegistry(): CommandRegistry {
        return $this->commandRegistry;
    }
    
    public function getProvider(): BaseProvider {
        return $this->provider;
    }
    
    public function getGroup(string $name) {
        return $this->provider->getGroup($name);
    }
    
    public function getUser(string $name) {
        return $this->provider->getUser($name);
    }
    
    public function getGroups(): array {
        return $this->provider->getGroups();
    }
    
    
    /**
     * Translates a message.
     *
     * @param string $text
     * @param array  $params
     *
     * @return string|null
     */
    public function translate(string $text, array $params = []) {
        if(!empty($params)) {
            return vsprintf($this->language->getNested($text), $params);
        }
        return $this->language->getNested($text);
    }
    
    /**
     * Set the data provider used by the plugin.
     *
     * @param string $provider
     */
    private function setProvider(string $provider) {
        switch($provider) {
            case "json":
                $provider = new JSONProvider($this);
                break;
            case "yaml":
            case "yml":
                $provider = new YAMLProvider($this);
                break;
            default:
                $this->log($this->translate("console.provider.not-found"));
                $provider = new YAMLProvider($this);
                break;
        }    
        $this->provider = $provider;
        $this->log(TF::YELLOW . $this->translate("console.provider.set", [$provider->getName()]));
    }
    
    /**
     * @param string $text
     */
    public function log(string $text) {
        $this->getServer()->getLogger()->info($this->prefix . $text);
    }
    
    public function warn(string $text) {
        $this->log(TF::BOLD . TF::RED . "[!] " . TF::RESET . TF::GOLD . $text);
    }
}
