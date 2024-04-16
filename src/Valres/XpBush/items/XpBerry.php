<?php

namespace Valres\XpBush\items;

use customiesdevs\customies\block\CustomiesBlockFactory;
use customiesdevs\customies\item\ItemComponents;
use customiesdevs\customies\item\ItemComponentsTrait;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;

class XpBerry extends Item implements ItemComponents
{
    use ItemComponentsTrait;

    public function __construct(ItemIdentifier $identifier) {
        $name = "Baies d'XP";
        parent::__construct($identifier, $name);
    }

    public function getBlock(?int $clickedFace = null): Block {
        return CustomiesBlockFactory::getInstance()->get("minecraft:xpbush_stage_0");
    }
}
