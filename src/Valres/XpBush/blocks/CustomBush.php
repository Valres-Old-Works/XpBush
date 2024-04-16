<?php

namespace Valres\XpBush\blocks;

use customiesdevs\customies\block\CustomiesBlockFactory;
use pocketmine\block\Block;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\BlockTypeTags;
use pocketmine\block\Dirt;
use pocketmine\block\Flowable;
use pocketmine\block\utils\FortuneDropHelper;
use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\event\block\BlockGrowEvent;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\item\Fertilizer;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

class CustomBush extends Flowable
{
    protected int $stage = 0;
    protected int $firststage = 0;
    protected int $laststage = 3;

    public function getBerryDropAmount() : int {
        if($this->stage === $this->laststage){
            return mt_rand(2, 3);
        } elseif ($this->stage >= $this->laststage - 1){
            return mt_rand(1, 2);
        }
        return 0;
    }

    protected function canBeSupportedBy(Block $block) : bool {
        return $block->getTypeId() !== BlockTypeIds::FARMLAND && ($block->hasTypeTag(BlockTypeTags::DIRT) || $block->hasTypeTag(BlockTypeTags::MUD));
    }

    public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool {
        if(!$this->canBeSupportedBy($blockReplace->getSide(Facing::DOWN))){
            return false;
        }
        return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []) : bool {
        if($this->stage < $this->laststage && $item instanceof Fertilizer){
            $block = clone $this;
            $block->stage ++;

            $ev = new BlockGrowEvent($this, $block, $player);
            $ev->call();

            if(!$ev->isCancelled()) $item->pop();
        }
        return true;
    }

    public function getDropsForCompatibleTool(Item $item) : array {
        $count = match($this->stage){
            $this->laststage => FortuneDropHelper::discrete($item, 2, 3),
            $this->laststage - 1 => FortuneDropHelper::discrete($item, 1, 2),
            default => 0
        };
        return [
            $this->asItem()->setCount($count)
        ];
    }

    public function onNearbyBlockChange() : void {
        if(!$this->getSide(Facing::DOWN) instanceof Dirt){
            $this->position->getWorld()->useBreakOn($this->position);
        }
    }

    public function ticksRandomly() : bool {
        return true;
    }

    public function onRandomTick() : void {
        if(!$this->IsMaxStage() && mt_rand(0, 2) === 1 ){
            $ev = new BlockGrowEvent($this, $this->getNewStateBlock());
            $ev->call();
            if(!$ev->isCancelled()){
                $this->position->getWorld()->setBlock($this->position, $this->getNewStateBlock());
                $this->stage = $this->stage + 1;
            }
        }
    }

    public function getFrictionFactor(): float {
        return 0.2;
    }

    public function IsMaxStage(): bool {
        return $this->stage == $this->laststage;
    }

    public function getNewStateBlock(): Block {
        return CustomiesBlockFactory::getInstance()->get("minecraft:" . $this->getName() . "_" . $this->stage + 1);
    }


    public function hasEntityCollision() : bool {
        return true;
    }

    public function onEntityInside(Entity $entity) : bool {
        if($this->stage >= $this->laststage && $entity instanceof Living){
            $entity->resetFallDistance();
            $entity->attack(new EntityDamageByBlockEvent($this, $entity, EntityDamageByBlockEvent::CAUSE_CONTACT, 1));
        }
        return true;
    }

    public function getMaxStage(): int {
        return $this->laststage;
    }

    public function getStage(): int {
        return $this->stage;
    }

    public function getFirstStage(): int {
        return $this->firststage;
    }

    public function setMaxStage(int $stage): void {
        $this->laststage = $stage;
    }

    public function setStage(int $stage): self {
        $this->stage = $stage;
        return $this;
    }

    public function setFirstStage(int $stage): void {
        $this->firststage = $stage;
    }
}
