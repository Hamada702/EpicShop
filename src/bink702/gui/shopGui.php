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

    public $economyAPI;
    public $shop;
    public $cfg;

    public $block;
    public $wooden;
    public $wool;
    public $decor;
    public $tera;
    public $tool;
    public $farm;
    public $food;


    public function openBlockShop($sender): void{
        $this->economyAPI = EpicShop::getInstance()->economyAPI;
        $this->block = EpicShop::getInstance()->block;
        $this->shop = EpicShop::getInstance()->shop;
        $this->block->readonly();
        $this->block->setListener([$this, "openBlockShop2"]);
        $this->block->setName(EpicShop::$instance->replace($sender, EpicShop::$instance->cfg->get("ShopName")));
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
                        $sender->sendMessage("§aYou bought $jum item(s).");
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
        $this->wool = EpicShop::getInstance()->wool;
        $this->shop = EpicShop::getInstance()->shop;
        $this->wool->readonly();
        $this->wool->setListener([$this, "openWoolShop2"]);
        $this->wool->setName(EpicShop::$instance->replace($sender, EpicShop::$instance->cfg->get("ShopName")));
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
                        $sender->sendMessage("§aYou bought $jum item(s).");
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
        $this->wooden = EpicShop::getInstance()->wooden;
        $this->shop = EpicShop::getInstance()->shop;
        $this->wooden->readonly();
        $this->wooden->setListener([$this, "openWoodenShop2"]);
        $this->wooden->setName(EpicShop::$instance->replace($sender, EpicShop::$instance->cfg->get("ShopName")));
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
                        $sender->sendMessage("§aYou bought $jum item(s).");
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

    public function openTeraShop($sender): void{
        $this->economyAPI = EpicShop::getInstance()->economyAPI;
        $this->tera = EpicShop::getInstance()->tera;
        $this->shop = EpicShop::getInstance()->shop;
        $this->tera->readonly();
        $this->tera->setListener([$this, "openTeraShop2"]);
        $this->tera->setName(EpicShop::$instance->replace($sender, EpicShop::$instance->cfg->get("ShopName")));
        $inventory = $this->tera->getInventory();
        $num = 0;
        foreach ($this->shop->getAll()["tera"] as $data){
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
        $this->tera->send($sender);
    }

    public function openTeraShop2(Player $sender, Item $item){
        $this->tera->readonly();
        $hand = $sender->getInventory()->getItemInHand()->getCustomName();
        $inventory = $this->tera->getInventory();
        $packet = new PlaySoundPacket();
        $packet->x = $sender->getX();
        $packet->y = $sender->getY();
        $packet->z = $sender->getZ();
        $packet->volume = 1;
        $packet->pitch = 1;
        foreach ($this->shop->getAll()["tera"] as $data){
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
                        $sender->sendMessage("§aYou bought $jum item(s).");
                        $packet->soundName = "thnks3";
                        $sender->sendDataPacket($packet);
                    } else {
                        $sender->sendMessage("§c§oYou don't have money to buy this item!");
                        $packet->soundName = "no3";
                        $sender->sendDataPacket($packet);
                    }
                }
                if($typ === "frame"){
                    break;
                }
                if($typ === "exit"){
                    $sender->removeWindow($inventory);
                    $packet->soundName = "by3";
                    $sender->sendDataPacket($packet);
                }
            }
        }
    }

    public function openDecorShop($sender): void{
        $this->economyAPI = EpicShop::getInstance()->economyAPI;
        $this->decor = EpicShop::getInstance()->decor;
        $this->shop = EpicShop::getInstance()->shop;
        $this->decor->readonly();
        $this->decor->setListener([$this, "openDecorShop2"]);
        $this->decor->setName(EpicShop::$instance->replace($sender, EpicShop::$instance->cfg->get("ShopName")));
        $inventory = $this->decor->getInventory();
        $num = 0;
        foreach ($this->shop->getAll()["decor"] as $data){
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
        $this->decor->send($sender);
    }

    public function openDecorShop2(Player $sender, Item $item){
        $this->decor->readonly();
        $hand = $sender->getInventory()->getItemInHand()->getCustomName();
        $inventory = $this->decor->getInventory();
        $packet = new PlaySoundPacket();
        $packet->x = $sender->getX();
        $packet->y = $sender->getY();
        $packet->z = $sender->getZ();
        $packet->volume = 1;
        $packet->pitch = 1;
        foreach ($this->shop->getAll()["decor"] as $data){
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
                        $sender->sendMessage("§aYou bought $jum item(s).");
                        $packet->soundName = "thnks4";
                        $sender->sendDataPacket($packet);
                    } else {
                        $sender->sendMessage("§c§oYou don't have money to buy this item!");
                        $packet->soundName = "no4";
                        $sender->sendDataPacket($packet);
                    }
                }
                if($typ === "frame"){
                    break;
                }
                if($typ === "exit"){
                    $sender->removeWindow($inventory);
                    $packet->soundName = "by4";
                    $sender->sendDataPacket($packet);
                }
            }
        }
    }

    public function openToolShop($sender): void{
        $this->economyAPI = EpicShop::getInstance()->economyAPI;
        $this->tool = EpicShop::getInstance()->tool;
        $this->shop = EpicShop::getInstance()->shop;
        $this->tool->readonly();
        $this->tool->setListener([$this, "openToolShop2"]);
        $this->tool->setName(EpicShop::$instance->replace($sender, EpicShop::$instance->cfg->get("ShopName")));
        $inventory = $this->tool->getInventory();
        $num = 0;
        foreach ($this->shop->getAll()["tool"] as $data){
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
        $this->tool->send($sender);
    }

    public function openToolShop2(Player $sender, Item $item){
        $this->tool->readonly();
        $hand = $sender->getInventory()->getItemInHand()->getCustomName();
        $inventory = $this->tool->getInventory();
        $packet = new PlaySoundPacket();
        $packet->x = $sender->getX();
        $packet->y = $sender->getY();
        $packet->z = $sender->getZ();
        $packet->volume = 1;
        $packet->pitch = 1;
        foreach ($this->shop->getAll()["tool"] as $data){
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
                        $sender->sendMessage("§aYou bought $jum item(s).");
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

    public function openFoodShop($sender): void{
        $this->economyAPI = EpicShop::getInstance()->economyAPI;
        $this->food = EpicShop::getInstance()->food;
        $this->shop = EpicShop::getInstance()->shop;
        $this->food->readonly();
        $this->food->setListener([$this, "openFoodShop2"]);
        $this->food->setName(EpicShop::$instance->replace($sender, EpicShop::$instance->cfg->get("ShopName")));
        $inventory = $this->food->getInventory();
        $num = 0;
        foreach ($this->shop->getAll()["food"] as $data){
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
        $this->food->send($sender);
    }

    public function openFoodShop2(Player $sender, Item $item){
        $this->food->readonly();
        $hand = $sender->getInventory()->getItemInHand()->getCustomName();
        $inventory = $this->food->getInventory();
        $packet = new PlaySoundPacket();
        $packet->x = $sender->getX();
        $packet->y = $sender->getY();
        $packet->z = $sender->getZ();
        $packet->volume = 1;
        $packet->pitch = 1;
        foreach ($this->shop->getAll()["food"] as $data){
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
                        $sender->sendMessage("§aYou bought $jum item(s).");
                        $packet->soundName = "thnks4";
                        $sender->sendDataPacket($packet);
                    } else {
                        $sender->sendMessage("§c§oYou don't have money to buy this item!");
                        $packet->soundName = "no4";
                        $sender->sendDataPacket($packet);
                    }
                }
                if($typ === "frame"){
                    break;
                }
                if($typ === "exit"){
                    $sender->removeWindow($inventory);
                    $packet->soundName = "by4";
                    $sender->sendDataPacket($packet);
                }
            }
        }
    }

    public function openFarmShop($sender): void{
        $this->economyAPI = EpicShop::getInstance()->economyAPI;
        $this->farm = EpicShop::getInstance()->farm;
        $this->shop = EpicShop::getInstance()->shop;
        $this->farm->readonly();
        $this->farm->setListener([$this, "openFarmShop2"]);
        $this->farm->setName(EpicShop::$instance->replace($sender, EpicShop::$instance->cfg->get("ShopName")));
        $inventory = $this->farm->getInventory();
        $num = 0;
        foreach ($this->shop->getAll()["farm"] as $data){
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
        $this->farm->send($sender);
    }

    public function openFarmShop2(Player $sender, Item $item){
        $this->farm->readonly();
        $hand = $sender->getInventory()->getItemInHand()->getCustomName();
        $inventory = $this->farm->getInventory();
        $packet = new PlaySoundPacket();
        $packet->x = $sender->getX();
        $packet->y = $sender->getY();
        $packet->z = $sender->getZ();
        $packet->volume = 1;
        $packet->pitch = 1;
        foreach ($this->shop->getAll()["farm"] as $data){
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
                        $sender->sendMessage("§aYou bought $jum item(s).");
                        $packet->soundName = "thnks3";
                        $sender->sendDataPacket($packet);
                    } else {
                        $sender->sendMessage("§c§oYou don't have money to buy this item!");
                        $packet->soundName = "no3";
                        $sender->sendDataPacket($packet);
                    }
                }
                if($typ === "frame"){
                    break;
                }
                if($typ === "exit"){
                    $sender->removeWindow($inventory);
                    $packet->soundName = "by3";
                    $sender->sendDataPacket($packet);
                }
            }
        }
    }

}
