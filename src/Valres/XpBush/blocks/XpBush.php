<?php

namespace Valres\XpBush\blocks;

use customiesdevs\customies\block\CustomiesBlockFactory;
use customiesdevs\customies\item\CustomiesItemFactory;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockTypeInfo;
use pocketmine\block\VanillaBlocks;
use pocketmine\entity\animation\ArmSwingAnimation;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\sound\ItemUseOnBlockSound;

class XpBush extends CustomBush
{
    public function __construct(BlockIdentifier $idInfo, string $name, BlockTypeInfo $typeInfo, int $age) {
        parent::__construct($idInfo, $name, $typeInfo);
        $this->setStage($age);
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool {
        if($this->getBerryDropAmount() > 0){
            $this->getPosition()->getWorld()->setBlock($this->getPosition(), CustomiesBlockFactory::getInstance()->get("minecraft:xpbush_stage_1"));
            $player->broadcastSound(new ItemUseOnBlockSound(VanillaBlocks::SWEET_BERRY_BUSH()));
            $player->broadcastAnimation(new ArmSwingAnimation($player));
            $player->getInventory()->addItem(CustomiesItemFactory::getInstance()->get("minecraft:xp_berry")->setCount($this->getBerryDropAmount()));
        }
        return parent::onInteract($item, $face, $clickVector, $player, $returnedItems);
    }

    public function getDrops(Item $item): array {
        return [CustomiesItemFactory::getInstance()->get("minecraft:xp_berry")->setCount($this->getBerryDropAmount())];
    }

    public function getFrictionFactor(): float {
        return 0.4;
    }

    public function getPickedItem(bool $addUserData = false): Item {
        return CustomiesItemFactory::getInstance()->get("minecraft:xp_berry");
    }
}
