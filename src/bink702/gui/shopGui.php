<?php

declare(strict_types=1);

namespace bink702\gui;

use bink702\cmd\shopForm;
use bink702\EpicShop;
use pocketmine\block\Thin;
use pocketmine\Player;
use onebone\economyapi\EconomyAPI;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use libs\muqsit\invmenu;
use pocketmine\plugin\PluginLoadOrder;
use pocketmine\scheduler\TaskScheduler;
use \pocketmine\utils\Config;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;

class shopGui extends PluginLoadOrder implements Listener {

    public $block;
    public $economyAPI;
    public Config $shop;




    public function openBlockShop($sender): void{
        $this->economyAPI = EpicShop::getInstance()->economyAPI;
        $this->block = EpicShop::getInstance()->block;
        $this->shop = EpicShop::getInstance()->shop;
        $this->block->readonly();
        $this->block->setListener([$this, "openBlockShop2"]);
        $this->block->setName(EpicShop::$instance->replace($sender,"BlockShop (Money: {money})"));
        $inventory = $this->block->getInventory();
        $num = 0;
        foreach ($this->shop->getAll()["block"] as $data){
            if($num >= 54){
                break;
            }else{
                $num++;
            }
            $dt = explode(":", $data);
            // slot:id:meta:jumlah:harga:type
            $no = $dt[0];
            $id = $dt[1];
            $meta = $dt[2];
            $jum = $dt[3];
            $har = $dt[4];
            $typ = $dt[5];
            // slot:id:meta:jumlah:harga:type
            if($typ === "buy") {
                $inventory->setItem((int)$no, Item::get((int)$id, (int)$meta, (int)$jum)->setLore(["\n§l§bBUY $jum: §a$har §r§o(Left-Click)"]));
            }
            if($typ === "frame"){
                $inventory->setItem((int)$no, Item::get((int)$id, (int)$meta, (int)$jum)->setCustomName("---"));
            }
            if($typ === "exit"){
                $inventory->setItem((int)$no, Item::get((int)$id, (int)$meta, (int)$jum)->setCustomName("EXIT"));
            }
        }
        $this->block->send($sender);
    }

    public function openBlockShop2(Player $sender, Item $item){
        $this->block->readonly();
        $hand = $sender->getInventory()->getItemInHand()->getCustomName();
        $inventory = $this->block->getInventory();
        $packet = new PlaySoundPacket();
        $packet->x = $sender->getX();
        $packet->y = $sender->getY();
        $packet->z = $sender->getZ();
        $packet->volume = 1;
        $packet->pitch = 1;
        foreach ($this->shop->getAll()["block"] as $data){
            $dt = explode(":", $data);
            // slot:id:meta:jumlah:harga:type
            $no = $dt[0];
            $id = $dt[1];
            $meta = $dt[2];
            $jum = $dt[3];
            $har = $dt[4];
            $typ = $dt[5];
            // slot:id:meta:jumlah:harga:type
            if($item->getId() == (int)$id && $item->getDamage() == (int)$meta){
                $money = $this->economyAPI->myMoney($sender);
                if($typ === "buy") {
                    if ($money >= (int)$har) {
                        $this->economyAPI->reduceMoney($sender, (int)$har);
                        $inv = $sender->getInventory();
                        $inv->addItem(Item::get((int)$id, (int)$meta, (int)$jum));
                        $sender->sendMessage("§aYou bought 64 item(s).");
                        $packet->soundName = "thnks5";
                        $sender->sendDataPacket($packet);
                    } else {
                        $sender->sendMessage("§c§oYou don't have money to buy this item!");
                        $packet->soundName = "no5";
                        $sender->sendDataPacket($packet);
                    }
                }
                if($typ === "frame"){
                    break;
                }
                if($typ === "exit"){
                    $sender->removeWindow($inventory);
                    $packet->soundName = "by5";
                    $sender->sendDataPacket($packet);
                }
            }
        }
    }

    public function openWoolShop($sender): void{
        $this->economyAPI = EpicShop::getInstance()->economyAPI;
        $this->wool = EpicShop::getInstance()->block;
        $this->shop = EpicShop::getInstance()->shop;
        $this->wool->readonly();
        $this->wool->setListener([$this, "openWoolShop2"]);
        $this->wool->setName(EpicShop::$instance->replace($sender,"BlockShop (Money: {money})"));
        $inventory = $this->wool->getInventory();
        $num = 0;
        foreach ($this->shop->getAll()["wool"] as $data){
            if($num >= 54){
                break;
            }else{
                $num++;
            }
            $dt = explode(":", $data);
            // slot:id:meta:jumlah:harga:type
            $no = $dt[0];
            $id = $dt[1];
            $meta = $dt[2];
            $jum = $dt[3];
            $har = $dt[4];
            $typ = $dt[5];
            // slot:id:meta:jumlah:harga:type
            if($typ === "buy") {
                $inventory->setItem((int)$no, Item::get((int)$id, (int)$meta, (int)$jum)->setLore(["\n§l§bBUY $jum: §a$har §r§o(Left-Click)"]));
            }
            if($typ === "frame"){
                $inventory->setItem((int)$no, Item::get((int)$id, (int)$meta, (int)$jum)->setCustomName("---"));
            }
            if($typ === "exit"){
                $inventory->setItem((int)$no, Item::get((int)$id, (int)$meta, (int)$jum)->setCustomName("EXIT"));
            }
        }
        $this->wool->send($sender);
    }

    public function openWoolShop2(Player $sender, Item $item){
        $this->wool->readonly();
        $hand = $sender->getInventory()->getItemInHand()->getCustomName();
        $inventory = $this->wool->getInventory();
        $packet = new PlaySoundPacket();
        $packet->x = $sender->getX();
        $packet->y = $sender->getY();
        $packet->z = $sender->getZ();
        $packet->volume = 1;
        $packet->pitch = 1;
        foreach ($this->shop->getAll()["wool"] as $data){
            $dt = explode(":", $data);
            // slot:id:meta:jumlah:harga:type
            $no = $dt[0];
            $id = $dt[1];
            $meta = $dt[2];
            $jum = $dt[3];
            $har = $dt[4];
            $typ = $dt[5];
            // slot:id:meta:jumlah:harga:type
            if($item->getId() == (int)$id && $item->getDamage() == (int)$meta){
                $money = $this->economyAPI->myMoney($sender);
                if($typ === "buy") {
                    if ($money >= (int)$har) {
                        $this->economyAPI->reduceMoney($sender, (int)$har);
                        $inv = $sender->getInventory();
                        $inv->addItem(Item::get((int)$id, (int)$meta, (int)$jum));
                        $sender->sendMessage("§aYou bought 64 item(s).");
                        $packet->soundName = "thnks2";
                        $sender->sendDataPacket($packet);
                    } else {
                        $sender->sendMessage("§c§oYou don't have money to buy this item!");
                        $packet->soundName = "no2";
                        $sender->sendDataPacket($packet);
                    }
                }
                if($typ === "frame"){
                    break;
                }
                if($typ === "exit"){
                    $sender->removeWindow($inventory);
                    $packet->soundName = "by2";
                    $sender->sendDataPacket($packet);
                }
            }
        }
    }


    public function openWoodenShop($sender): void{
        $this->economyAPI = EpicShop::getInstance()->economyAPI;
        $this->wooden = EpicShop::getInstance()->block;
        $this->shop = EpicShop::getInstance()->shop;
        $this->wooden->readonly();
        $this->wooden->setListener([$this, "openWoodenShop2"]);
        $this->wooden->setName(EpicShop::$instance->replace($sender,"BlockShop (Money: {money})"));
        $inventory = $this->wooden->getInventory();
        $num = 0;
        foreach ($this->shop->getAll()["wooden"] as $data){
            if($num >= 54){
                break;
            }else{
                $num++;
            }
            $dt = explode(":", $data);
            // slot:id:meta:jumlah:harga:type
            $no = $dt[0];
            $id = $dt[1];
            $meta = $dt[2];
            $jum = $dt[3];
            $har = $dt[4];
            $typ = $dt[5];
            // slot:id:meta:jumlah:harga:type
            if($typ === "buy") {
                $inventory->setItem((int)$no, Item::get((int)$id, (int)$meta, (int)$jum)->setLore(["\n§l§bBUY $jum: §a$har §r§o(Left-Click)"]));
            }
            if($typ === "frame"){
                $inventory->setItem((int)$no, Item::get((int)$id, (int)$meta, (int)$jum)->setCustomName("---"));
            }
            if($typ === "exit"){
                $inventory->setItem((int)$no, Item::get((int)$id, (int)$meta, (int)$jum)->setCustomName("EXIT"));
            }
        }
        $this->wooden->send($sender);
    }

    public function openWoodenShop2(Player $sender, Item $item){
        $this->wooden->readonly();
        $hand = $sender->getInventory()->getItemInHand()->getCustomName();
        $inventory = $this->wooden->getInventory();
        $packet = new PlaySoundPacket();
        $packet->x = $sender->getX();
        $packet->y = $sender->getY();
        $packet->z = $sender->getZ();
        $packet->volume = 1;
        $packet->pitch = 1;
        foreach ($this->shop->getAll()["wooden"] as $data){
            $dt = explode(":", $data);
            // slot:id:meta:jumlah:harga:type
            $no = $dt[0];
            $id = $dt[1];
            $meta = $dt[2];
            $jum = $dt[3];
            $har = $dt[4];
            $typ = $dt[5];
            // slot:id:meta:jumlah:harga:type
            if($item->getId() == (int)$id && $item->getDamage() == (int)$meta){
                $money = $this->economyAPI->myMoney($sender);
                if($typ === "buy") {
                    if ($money >= (int)$har) {
                        $this->economyAPI->reduceMoney($sender, (int)$har);
                        $inv = $sender->getInventory();
                        $inv->addItem(Item::get((int)$id, (int)$meta, (int)$jum));
                        $sender->sendMessage("§aYou bought 64 item(s).");
                        $packet->soundName = "thnks1";
                        $sender->sendDataPacket($packet);
                    } else {
                        $sender->sendMessage("§c§oYou don't have money to buy this item!");
                        $packet->soundName = "no1";
                        $sender->sendDataPacket($packet);
                    }
                }
                if($typ === "frame"){
                    break;
                }
                if($typ === "exit"){
                    $sender->removeWindow($inventory);
                    $packet->soundName = "by1";
                    $sender->sendDataPacket($packet);
                }
            }
        }
    }

}