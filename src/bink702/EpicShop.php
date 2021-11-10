<?php

declare(strict_types=1);

namespace bink702;

use bink702\gui\shopGui;
use pocketmine\command\Command;
use pocketmine\command\CommandMap;
use pocketmine\command\CommandSender;
use pocketmine\command\SimpleCommandMap;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use ReflectionClass;
use pocketmine\resourcepacks\ZippedResourcePack;

use pocketmine\network\mcpe\protocol\PlaySoundPacket;

# lib
use libs\muqsit\invmenu\InvMenu;
use libs\muqsit\invmenu\InvMenuHandler;
use onebone\economyapi\EconomyAPI;
use libs\FormAPI\SimpleForm;

# cmd
use bink702\cmd\shopForm;

class EpicShop extends PluginBase{

    /** @var EpicShop */
    public static $instance;

    /** @var InvMenu */
    public $block;
    public $menu;
    public $wooden;
    public $wool;
    public $potion;
    public $decor;
    public $tera;
    public $tool;
    public $farm;
    public $food;

    /** @var Config */
    public $cfg;
    public Config $shop;

    /** @var economyAPI */
    public $economyAPI;



    public function onLoad(){
        self::$instance = $this;
    }
    public static function getInstance(){
        return self::$instance;
    }


    public function onEnable(): void
    {
        $this->saveResource("shop.yml");
        $this->saveDefaultConfig();
        $this->shop = new Config($this->getDataFolder() . "shop.yml", Config::YAML);
        $this->saveDefaultConfig();
        $this->cfg = $this->getConfig();
        $this->economyAPI = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        $this->getServer()->getCommandMap()->register("shop", new cmd\shopForm($this));
        $this->menu = InvMenu::create(InvMenu::TYPE_CHEST);
        $this->block = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
        $this->wooden = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
        $this->wool = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
        $this->potion = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
        $this->decor = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
        $this->tera = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
        $this->tool = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
        $this->food = InvMenu::create(InvMenu::TYPE_CHEST);
        $this->farm = InvMenu::create(InvMenu::TYPE_CHEST);
        if(!InvMenuHandler::isRegistered()){
            InvMenuHandler::register($this);
        }

        /* This original code by https://github.com/NhanAZ **/
        /* start here **/
        $this->saveResource("shopSound.mcpack", true);

        $manager = $this->getServer()->getResourcePackManager();
        $pack = new ZippedResourcePack($this->getDataFolder() . "shopSound.mcpack");

        $reflection = new ReflectionClass($manager);

        $property = $reflection->getProperty("resourcePacks");
        $property->setAccessible(true);

        $currentResourcePacks = $property->getValue($manager);
        $currentResourcePacks[] = $pack;
        $property->setValue($manager, $currentResourcePacks);

        $property = $reflection->getProperty("uuidList");
        $property->setAccessible(true);
        $currentUUIDPacks = $property->getValue($manager);
        $currentUUIDPacks[strtolower($pack->getPackId())] = $pack;
        $property->setValue($manager, $currentUUIDPacks);

        $property = $reflection->getProperty("serverForceResources");
        $property->setAccessible(true);
        $property->setValue($manager, true);
        /* to8 here **/
    }

    public function openShopForm($player): SimpleForm
    {
        $form = new SimpleForm(function (Player $player, $data){
            $result = $data;
            if($result === null){
                return true;
            }
            $packet = new PlaySoundPacket();
            $packet->x = $player->getX();
            $packet->y = $player->getY();
            $packet->z = $player->getZ();
            $packet->volume = 1;
            $packet->pitch = 1;
            switch ($result){
                case 0:
                    $menu = new shopGui(self::getInstance());
                    $packet->soundName = "wellcome5";
                    $player->sendDataPacket($packet);
                    $menu->openBlockShop($player);
                    break;
                case 1:
                    $menu = new shopGui(self::getInstance());
                    $packet->soundName = "wellcome2";
                    $player->sendDataPacket($packet);
                    $menu->openWoolShop($player);
                    break;
                case 2:
                    $menu = new shopGui(self::getInstance());
                    $packet->soundName = "wellcome1";
                    $player->sendDataPacket($packet);
                    $menu->openWoodenShop($player);
                    break;
            }
        });
        $form->setTitle($this->cfg->get("Title"));
        $form->addButton("Block Shop", 0, "textures/blocks/dirt.png");
        $form->addButton("Wool Shop", 0, "textures/blocks/wool_colored_white.png");
        $form->addButton("Wooden Shop", 0, "textures/blocks/log_oak.png");
        $form->addButton("Block", 0, "textures/blocks/dirt.png");
        $form->addButton("Block", 0, "textures/blocks/dirt.png");
        $form->addButton("Block", 0, "textures/blocks/dirt.png");
        $form->addButton("Block", 0, "textures/blocks/dirt.png");
        $form->addButton("Block", 0, "textures/blocks/dirt.png");
        $form->addButton("Block", 0, "textures/blocks/dirt.png");
        $form->addButton("Block", 0, "textures/blocks/dirt.png");
        $form->sendToPlayer($player);
        return $form;
    }


    public static function converterMoney($n, $precision = 1): string
    {
        if ($n < 900) {
            // 0 - 900
            $n_format = number_format($n / 100, $precision);
            $suffix = '§eC§r';
        } else if ($n < 900000) {
            // 0.9k-850k
            $n_format = number_format($n / 1000, $precision);
            $suffix = '§eK§r';
        } else if ($n < 900000000) {
            // 0.9m-850m
            $n_format = number_format($n / 1000000, $precision);
            $suffix = '§eM§r';
        } else if ($n < 900000000000) {
            // 0.9b-850b
            $n_format = number_format($n / 1000000000, $precision);
            $suffix = '§eB§r';
        } else {
            // 0.9t+
            $n_format = number_format($n / 1000000000000, $precision);
            $suffix = '§eT§r';
        }
        if ($precision > 0) {
            $dotzero = '.' . str_repeat('0', $precision);
            $n_format = str_replace($dotzero, '', $n_format);
        }

        return $n_format . $suffix;
    }

    public function replace($player, $location): array|string
    {

        $from = ["{player}", "{money}"];
        $to = [$player->getName(),$this->economyAPI->myMoney($player) !== null ? $this->converterMoney($this->economyAPI->myMoney($player)) : 0];
        $replace = str_replace($from, $to, $location);
        return $replace;
    }


}
