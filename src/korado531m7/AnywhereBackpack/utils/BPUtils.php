<?php
namespace korado531m7\AnywhereBackpack\utils;

use pocketmine\Player;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;
use pocketmine\item\Item;
use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\BlockActorDataPacket as BlockEntityDataPacket;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;

class BPUtils{
    public static function sendFakeBlock(Player $player,int $x,int $y,int $z,int $blockid){
        $pk = new UpdateBlockPacket();
        $pk->x = $x;
        $pk->y = $y;
        $pk->z = $z;
        $pk->flags = UpdateBlockPacket::FLAG_ALL;
        $pk->blockRuntimeId = BlockFactory::toStaticRuntimeId($blockid);
        $player->dataPacket($pk);
    }
    
    public static function sendTagData(Player $player, CompoundTag $tag, int $x, int $y, int $z){
        $writer = new NetworkLittleEndianNBTStream();
        $pk = new BlockEntityDataPacket;
        $pk->x = $x;
        $pk->y = $y;
        $pk->z = $z;
        $pk->namedtag = $writer->write($tag);
        $player->dataPacket($pk);
    }
    
    public static function sendFakeChest(Player $player, int $x, int $y, int $z, bool $isAir = false){
        $id = $isAir ? BlockIds::AIR : BlockIds::CHEST;
        self::sendFakeBlock($player, $x, $y, $z, $id);
        self::sendFakeBlock($player, $x, $y, $z + 1, $id);
    }
    
    public static function getIdFromItem(Item $item) : ?int{
        $lore = $item->getLore();
        return ($lore[2] ?? null) === null ? null : substr($lore[2], 3);
    }
    
    public static function setIdToItem(Item $item, int $id) : Item{
        return $item->setLore(['','§aBackpack ID','§e'.$id]);
    }
}
