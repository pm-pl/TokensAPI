<?php

declare(strict_types=1);

namespace Terpz710\TokensAPI\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

use pocketmine\player\Player;

use Terpz710\TokensAPI\Tokens;

class PayTokens extends Command implements PluginOwned {

    private $plugin;

    public function __construct() {
        parent::__construct("paytoken");
        $this->setPermission("tokensapi.cmd.paytoken");
        $this->setDescription("Pay tokens to another player");
        $this->setUsage("Usage: /paytoken <player> <amount>");
        
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
        $senderTokens = $tokenAPI->getTokenBalance($sender);
        if ($senderTokens < $amount) {
            $sender->sendMessage("You don't have enough §etokens§f to complete this transaction!");
            return false;
        }

        $tokenAPI->removeToken($sender, $amount);
        $tokenAPI->addToken($targetPlayer, $amount);

        $sender->sendMessage("You have paid §e{$amount} tokens§f to §e" . $targetPlayer->getName() . "§f!");
        $targetPlayer->sendMessage("You have received §e{$amount} tokens§f from §e" . $sender->getName() . "§f!");

        return true;
    }

    public function getOwningPlugin() : Plugin{
        return $this->plugin;
    }
}
