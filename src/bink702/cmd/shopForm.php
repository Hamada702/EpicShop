<?php
namespace bink702\cmd;

use bink702\EpicShop;
use bink702\gui\blockGui;
use bink702\gui\shopGui;
use libs\FormAPI\SimpleForm;
use pocketmine\command\PluginCommand;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginLoader;

class shopForm extends PluginCommand
{

    /**
     * @var Plugin
     */
    private $plugin;
    private $cfg;
    private $deskpl = "§l§aName: §6EpicShop\n§l§aAuthor: §6bink702\n§l§aDiscord: §6hamdani#6477";

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
        $n = $sender->getName();
        $player = $sender->getServer()->getPlayer($n);
        $menu = new shopGui(EpicShop::getInstance());
        $packet = new PlaySoundPacket();
        $packet->x = $player->getX();
        $packet->y = $player->getY();
        $packet->z = $player->getZ();
        $packet->volume = 1;
        $packet->pitch = 1;
        if($sender instanceof Player) {
            if (!isset($args[0])) {
                $sender->sendMessage("§l§a==================\n");
                $sender->sendMessage($this->deskpl);
                $sender->sendMessage("§l§a==================\n");
                return true;
            }
            $arg = array_shift($args);
            switch($arg){
                case "menu":
                    $this->plugin->openShopForm($sender);
                    break;
                case "list":
                    $sender->sendMessage("§l§a==================\n");
                    $sender->sendMessage("§l§6/shop block\n§l§6/shop wooden\n§l§6/shop wool\n§l§6/shop decor\n§l§6/shop tera\§l§6/shop tool\n§l§6/shop farm\n§l§6/shop food");
                    $sender->sendMessage("§l§a==================\n");
                    break;
                case "block":
                    $packet->soundName = "wellcome5";
                    $player->sendDataPacket($packet);
                    $menu->openBlockShop($player);
                    break;
                case "wool":
                    $packet->soundName = "wellcome2";
                    $player->sendDataPacket($packet);
                    $menu->openWoolShop($player);
                    break;
                case "wooden":
                    $packet->soundName = "wellcome1";
                    $player->sendDataPacket($packet);
                    $menu->openWoodenShop($player);
                    break;
                case "tera":
                    $packet->soundName = "wellcome3";
                    $player->sendDataPacket($packet);
                    $menu->openTeraShop($player);
                    break;
                case "decor":
                    $packet->soundName = "wellcome4";
                    $player->sendDataPacket($packet);
                    $menu->openDecorShop($player);
                    break;
                case "tool":
                    $packet->soundName = "wellcome1";
                    $player->sendDataPacket($packet);
                    $menu->openToolShop($player);
                    break;
                case "food":
                    $packet->soundName = "wellcome4";
                    $player->sendDataPacket($packet);
                    $menu->openFoodShop($player);
                    break;
                case "farm":
                    $packet->soundName = "wellcome3";
                    $player->sendDataPacket($packet);
                    $menu->openFarmShop($player);
                    break;
                case "help":
                    $sender->sendMessage("§l§a==================\n");
                    $sender->sendMessage("§l§6/shop help\n§l§6/shop menu\n§l§6/shop list");
                    $sender->sendMessage("§l§a==================\n");
                    break;
            }

        }

        return true;
    }


}