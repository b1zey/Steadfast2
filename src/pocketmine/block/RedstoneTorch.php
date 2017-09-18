<?php

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\math\Vector3;

class RedstoneTorch extends RedstoneTorchActive {

	protected $id = self::REDSTONE_TORCH;

	public function __construct($meta = 0) {
		$this->meta = $meta;
	}

	public function getName() {
		return "Redstone Torch";
	}

	public function getLightLevel() {
		return 0;
	}

	public function getDrops(Item $item) {
		return [
			[self::REDSTONE_TORCH_ACTIVE, 0, 1],
		];
	}
	
	public function onUpdate($type) {
		static $faces = [
			1 => 4,
			2 => 5,
			3 => 2,
			4 => 3,
			5 => 0,
		];
		
		if ($this->meta == 5) { // placed on top of block
			$block = $this->getSide(Vector3::SIDE_DOWN);
			if ($block instanceof Air) {
				$this->getLevel()->useBreakOn($this);
				return;
			}
		} else {
			$block = $this->getSide($faces[$this->meta]);
			if ($block->isTransparent()) {
				$this->getLevel()->useBreakOn($this);
				return;
			}
		}
		$this->level->scheduleUpdate($this, 5);
		if ($block->isSolid() && $block->getPoweredState() == Solid::POWERED_NONE) {
			$litTorch = Block::get(Block::REDSTONE_TORCH_ACTIVE, $this->meta);
			$this->level->setBlock($this, $litTorch, true, true);
		}			
	}

}
