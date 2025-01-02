<?php

declare(strict_types=1);

namespace Terpz710\TokensAPI\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

use pocketmine\player\Player;

use Terpz710\TokensAPI\Tokens;

class AddTokens extends Command implements PluginOwned {

    private $plugin;

    public function __construct() {
        parent::__construct("addtoken");
        $this->setDescription("Add tokens to a player's balance");
        $this->setUsage("Usage: /addtoken <player> <amount>");
        $this->setPermission("tokensapi.cmd.addtoken");
        
        $this->plugin = Tokens::getInstance();
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used in-game!");
            return false;
        }

        if (!$this->testPermission($sender)) {
            return false;
        }

        if (count($args) !== 2) {
            $sender->sendMessage($this->getUsage());
            return false;
        }

        $targetPlayer = $this->plugin->getServer()->getPlayerExact($args[0]);
        if (!$targetPlayer instanceof Player) {
            $sender->sendMessage("Player not found!");
            return false;
        }

        $amount = (int) $args[1];
        if ($amount <= 0) {
            $sender->sendMessage("Please enter a valid amount greater than §c0§f!");
            return false;
        }

        $tokenAPI = $this->plugin->getTokenAPI();
        $tokenAPI->addToken($targetPlayer, $amount);

        $sender->sendMessage("§e{$amount} tokens§f have been added to §e" . $targetPlayer->getName() . "'s §fbalance!");

        return true;
    }

    public function getOwningPlugin() : Plugin{
        return $this->plugin;
    }
}
