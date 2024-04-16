<?php

namespace Valres\XpBush\items;

use customiesdevs\customies\block\CustomiesBlockFactory;
use customiesdevs\customies\item\CreativeInventoryInfo;
use customiesdevs\customies\item\ItemComponents;
use customiesdevs\customies\item\ItemComponentsTrait;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemUseResult;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class XpBerry extends Item implements ItemComponents
{
    use ItemComponentsTrait;

    public function __construct(ItemIdentifier $identifier) {
        $name = "Baies d'XP";
        parent::__construct($identifier, $name);

        $creativeInfo = new CreativeInventoryInfo(
            CreativeInventoryInfo::CATEGORY_NATURE
        );
        $this->initComponent("xp_berry", $creativeInfo);
    }

    public function getBlock(?int $clickedFace = null): Block {
        return CustomiesBlockFactory::getInstance()->get("minecraft:xpbush_stage_0");
    }

    public function onClickAir(Player $player, Vector3 $directionVector, array &$returnedItems): ItemUseResult
    {
        if($player->isSneaking()){
            $this->pop();
            $player->getXpManager()->addXp(rand(1, 3));
        }
        return parent::onClickAir($player, $directionVector, $returnedItems);
    }
}
