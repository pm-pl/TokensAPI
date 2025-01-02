<?php

declare(strict_types=1);

namespace Terpz710\TokensAPI\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

use pocketmine\player\Player;

use Terpz710\TokensAPI\Tokens;

class SeeTokens extends Command implements PluginOwned {

    private $plugin;

    public function __construct() {
        parent::__construct("seetoken");
        $this->setDescription("View the token balance of another player");
        $this->setUsage("Usage: /seetokens <player>");
        $this->setPermission("tokensapi.cmd.seetoken");
        
        $this->plugin = Tokens::getInstance();
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
        if (!$this->testPermission($sender)) {
            return false;
        }

        if (count($args) !== 1) {
            $sender->sendMessage($this->getUsage());
            return false;
        }

        $targetPlayer = $this->plugin->getServer()->getPlayerExact($args[0]);
        if (!$targetPlayer instanceof Player) {
            $sender->sendMessage("Player not found!");
            return false;
        }

        $tokenAPI = $this->plugin->getTokenAPI();
        $tokens = $tokenAPI->getTokenBalance($targetPlayer);

        $sender->sendMessage("§e" . $targetPlayer->getName() . "'s token balance:§e $tokens");

        return true;
    }

    public function getOwningPlugin() : Plugin{
        return $this->plugin;
    }
}
