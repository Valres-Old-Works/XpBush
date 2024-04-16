<?php

namespace Valres\XpBush;

use Brick\Math\Exception\MathException;
use customiesdevs\customies\block\CustomiesBlockFactory;
use customiesdevs\customies\block\Material;
use customiesdevs\customies\block\Model;
use customiesdevs\customies\item\CustomiesItemFactory;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\BlockTypeInfo;
use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use Valres\XpBush\blocks\XpBush as XpBushBlock;
use Valres\XpBush\items\XpBerry;

class XpBush extends PluginBase
{
    use SingletonTrait;

    protected function onEnable(): void {
        $stages = [0, 1, 2, 3];
        foreach($stages as $stage){
            $material = new Material(Material::TARGET_ALL, "xpbush_stage_" . $stage, Material::RENDER_METHOD_ALPHA_TEST);
            $model = new Model([$material], "geometry.custom_bush", new Vector3(-8, 0, -8), new Vector3(16, 4 + (3 * $stage), 16), 0);
            CustomiesBlockFactory::getInstance()->registerBlock(static fn () => new XpBushBlock(new BlockIdentifier(BlockTypeIds::newId()), "xpbush_stage", new BlockTypeInfo(new BlockBreakInfo(0)), $stage), "minecraft:xpbush_stage_" . $stage, $model);
        }
        CustomiesItemFactory::getInstance()->registerItem(XpBerry::class, "minecraft:xp_berry", "Baies d'XP");
    }
}
