<?php
namespace bink702\cmd;

use bink702\EpicShop;
use libs\FormAPI\SimpleForm;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\command\CommandSender;

class shopForm extends PluginCommand
{

    /**
     * @var Plugin
     */
    private $Plugin;
    private $cfg;

    public function __construct(EpicShop $shop){
        $this->Plugin = $shop;
        $this->cfg = $this->Plugin->getConfig();
        parent::__construct('shop', $shop);
    }

    public function Plugin() {
        return $this->Plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if($sender instanceof Player){
            $this->ShopForm($sender);
        }
        return true;
    }

    private function ShopForm($player){
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
        $form->setTitle($this->cfg->get("Title"));
        $form->addButton("Block", 0, "textures/blocks/dirt.png");
        $form->sendToPlayer($player);
        return $form;
    }

}