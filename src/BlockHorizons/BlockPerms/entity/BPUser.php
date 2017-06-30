<?php

namespace BlockHorizons\BlockPerms\entity;

use BlockHorizons\BlockPerms\Loader;

class BPUser {
	/** @var array */
	private $data = [];

	public function __construct(array $data) {
		$this->data = $data;
	}
    
	public function getName(): string {
		return $this->data["username"];
	}
    
	public function getGroups(): array {
		return $this->data["groups"] ?? [];
	}
    
	public function getPermissions(): array {
		return $this->data["permissions"] ?? [];
	}
}
