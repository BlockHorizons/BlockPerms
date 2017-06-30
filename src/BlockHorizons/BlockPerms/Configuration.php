<?php

namespace BlockHorizons\BlockPerms;

class Configuration {
	/** @var array */
	private static $data = [];

	public static function init(array $data) {
		self::$data = $data;
	}
    
	public static function getLanguage(): string {
		return self::$data["language"] ?? "en";
	}
    
	public static function getProvider(): string {
		return self::$data["provider"] ?? "json";
	}
}
