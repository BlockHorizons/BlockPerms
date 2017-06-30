<?php

namespace BlockHorizons\BlockPerms\entity;

use BlockHorizons\BlockPerms\Loader;

class BPGroup {
    /** @var array */
    private $data = [];

    public function __construct(array $data) {
        $this->data = $data;
    }
    
    public function getName(): string {
        return $this->data["name"];
    }
    
    public function getDisplayName(): string {
        return $this->data["displayName"];
    }
    
    public function getPermissions(): array {
        return $this->data["permissions"] ?? [];
    }
    
    public static function isValidName(): bool {
        return preg_match('/[0-9a-zA-Z\xA1-\xFE]$/', $this->getName());
    }
    
    public function create() {
        Loader::getInstance()->getProvider()->addGroup($this, $this->data);
    }
}
