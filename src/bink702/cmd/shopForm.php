<?php
namespace bink702\cmd;

use bink702\EpicShop;
use bink702\gui\blockGui;
use libs\FormAPI\SimpleForm;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginLoader;

class shopForm extends PluginCommand
{

    /**
     * @var Plugin
     */
    private $plugin;
    private $cfg;

    public function __construct(EpicShop $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct("shop", $plugin);
        $this->setUsage("/shop");
        $this->setDescription("Open Shop");
    }

    public function plugin() {
        return $this->plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if($sender instanceof Player){
            $this->plugin->openShopForm($sender);
        }
        return true;
    }



}