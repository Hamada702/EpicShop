<?php

declare(strict_types=1);

namespace bink702;

use pocketmine\command\Command;
use pocketmine\command\CommandMap;
use pocketmine\command\CommandSender;
use pocketmine\command\SimpleCommandMap;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

# lib
use libs\muqsit\invmenu\InvMenu;
use libs\muqsit\invmenu\InvMenuHandler;
use onebone\economyapi\EconomyAPI;
use libs\FormAPI\SimpleForm;

# cmd
use bink702\cmd\shopForm;

class EpicShop extends PluginBase{


    public function onEnable()
    {
        $this->saveResource("config.yml");
        $this->saveDefaultConfig();
        $this->getServer()->getCommandMap()->register("shop", new cmd\shopForm($this));
    }

    public function ShopForm($player){
        $form = new SimpleForm(function (Player $player, $data){
            $result = $data;
            if($result === null){
                return true;
            }
            switch ($result){
                case 0:
                    break;
            }
        });
        $form->setTitle("Sho8p");
        $form->addButton("Block", 0, "textures/blocks/dirt.png");
        $form->sendToPlayer($player);
        return $form;
    }




}
