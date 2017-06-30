<?php

namespace BlockHorizons\BlockPerms\util;

use pocketmine\utils\TextFormat as TF;

class TextUtil {

	public static function titleize(string $text): string {
		$length = 7;
		return TF::GOLD . str_repeat("_", $length) . ".[ " . TF::WHITE . $text . TF::GOLD . " ]." . str_repeat("_", $length);
	}
}
