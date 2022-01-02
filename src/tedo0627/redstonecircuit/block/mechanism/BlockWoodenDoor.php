<?php

namespace tedo0627\redstonecircuit\block\mechanism;

use pocketmine\block\Door;
use pocketmine\block\WoodenDoor;
use pocketmine\math\Facing;
use pocketmine\world\sound\DoorSound;
use tedo0627\redstonecircuit\block\BlockPowerHelper;
use tedo0627\redstonecircuit\block\IRedstoneComponent;
use tedo0627\redstonecircuit\block\RedstoneComponentTrait;

class BlockWoodenDoor extends WoodenDoor implements IRedstoneComponent {
    use RedstoneComponentTrait;

    public function onRedstoneUpdate(): void {
        $other = $this->getSide($this->isTop() ? Facing::DOWN : Facing::UP);
        $powered = BlockPowerHelper::isPowered($this) || BlockPowerHelper::isPowered($other);
        $world = $this->getPosition()->getWorld();
        if ($powered && !$this->isPowered()) {
            $this->setPowered(true);
            if (!$this->isOpen()) {
                $this->setOpen(true);
                $world->addSound($this->getPosition(), new DoorSound());
            }

            if ($other instanceof Door && $this->isSameType($other)) {
                $other->setPowered(true);
                $other->setOpen(true);
                $world->setBlock($other->getPosition(), $other);
            }
            $world->setBlock($this->getPosition(), $this);
            return;
        }

        if (!$powered && $this->isPowered()) {
            $this->setPowered(false);
            if ($this->isOpen()) {
                $this->setOpen(false);
                $world->addSound($this->getPosition(), new DoorSound());
            }

            if ($other instanceof Door && $this->isSameType($other)) {
                $other->setPowered(false);
                $other->setOpen(false);
                $world->setBlock($other->getPosition(), $other);
            }
            $world->setBlock($this->getPosition(), $this);
        }
    }
}