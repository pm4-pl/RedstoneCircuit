<?php

namespace tedo0627\redstonecircuit\block\mechanism;

use pocketmine\block\WoodenTrapdoor;
use pocketmine\world\sound\DoorSound;
use tedo0627\redstonecircuit\block\BlockPowerHelper;
use tedo0627\redstonecircuit\block\IRedstoneComponent;
use tedo0627\redstonecircuit\block\RedstoneComponentTrait;

class BlockWoodenTrapdoor extends WoodenTrapdoor implements IRedstoneComponent {
    use RedstoneComponentTrait;

    public function onRedstoneUpdate(): void {
        $power = BlockPowerHelper::isPowered($this);
        $world = $this->getPosition()->getWorld();
        if ($power && !$this->isOpen()) {
            $this->setOpen(true);
            $world->setBlock($this->getPosition(), $this);
            $world->addSound($this->getPosition(), new DoorSound());
            return;
        }

        if ($power || !$this->isOpen()) return;

        $this->setOpen(false);
        $world->setBlock($this->getPosition(), $this);
        $world->addSound($this->getPosition(), new DoorSound());
    }
}