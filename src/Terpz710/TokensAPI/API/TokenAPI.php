<?php

declare(strict_types=1);

namespace Terpz710\TokensAPI\API;

use pocketmine\player\Player;

use pocketmine\utils\SingletonTrait;

use Terpz710\TokensAPI\Tokens;

final class TokenAPI {
    use SingletonTrait;

    private $plugin;
    private $playerTokens = [];

    public function __construct() {
        $this->plugin = Tokens::getInstance();
        $this->loadPlayerTokens();
    }

    public function addToken(Player $player, int $amount) : void{
        $playerName = $player->getName();
        $currentTokens = $this->getTokenBalance($player);
        $newTokens = $currentTokens + $amount;
        $this->setPlayerToken($playerName, $newTokens);
    }

    public function removeToken(Player $player, int $amount) : bool{
        $playerName = $player->getName();
        $currentTokens = $this->getTokenBalance($player);
        if ($currentTokens >= $amount) {
            $newTokens = $currentTokens - $amount;
            $this->setPlayerToken($playerName, $newTokens);
            return true;
        }
        return false;
    }

    public function setToken(Player $player, int $amount) : void{
        $playerName = $player->getName();
        $this->setPlayerToken($playerName, $amount);
    }

    public function getTokenBalance(Player $player) : int{
        $playerName = $player->getName();
        if (isset($this->playerTokens[$playerName])) {
            return (int)$this->playerTokens[$playerName];
        }
        return 0;
    }

    private function loadPlayerTokens() : void{
        $dataFolder = $this->plugin->getDataFolder();
        $playerTokensFile = $dataFolder . "player_tokens.json";
        if (file_exists($playerTokensFile)) {
            $this->playerTokens = json_decode(file_get_contents($playerTokensFile), true);
        }
    }

    private function savePlayerTokens() : void{
        $dataFolder = $this->plugin->getDataFolder();
        $playerTokensFile = $dataFolder . "player_tokens.json";
        file_put_contents($playerTokensFile, json_encode($this->playerTokens, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function setPlayerToken(string $playerName, int $amount) : void{
        $this->playerTokens[$playerName] = $amount;
        $this->savePlayerTokens();
    }
}
