<?php

declare(strict_types=1);

namespace Terpz710\TokensAPI\API;

use pocketmine\player\Player;

use pocketmine\utils\SingletonTrait;

use Terpz710\TokensAPI\Tokens;

final class TokenAPI {
    use SingletonTrait;

    private $plugin;
    
    private string $dataFile;
    
    private array $data = [];

    public function __construct() {
        $this->plugin = Tokens::getInstance();
        $this->dataFile = $this->plugin->getDataFolder() . "tokens.json";

        if (file_exists($this->dataFile)) {
            $this->data = json_decode(file_get_contents($this->dataFile), true);
        }
    }

    public function createBalance(Player $player) : void{
        $uuid = $player->getUniqueId()->toString();
        $name = $player->getName();

        if (!isset($this->data[$uuid])) {
            $this->data[$uuid] = [
                "name" => $name,
                "balance" => $this->plugin->getConfig()->get("starting_token_amount")
            ];
        } else {
            $this->data[$uuid]["name"] = $name;
        }

        $this->save();
    }

    public function getTokenBalance(Player|string $identifier) : int{
        $uuid = $identifier instanceof Player ? $identifier->getUniqueId()->toString() : $identifier;
        return $this->data[$uuid]["balance"] ?? 0;
    }

    public function addToken(Player|string $identifier, int $amount) : void{
        $uuid = $identifier instanceof Player ? $identifier->getUniqueId()->toString() : $identifier;
        if (isset($this->data[$uuid])) {
            $this->data[$uuid]["balance"] += $amount;
            $this->save();
        }
    }

    public function removeToken(Player|string $identifier, int $amount) : void{
        $uuid = $identifier instanceof Player ? $identifier->getUniqueId()->toString() : $identifier;
        if (isset($this->data[$uuid])) {
            $this->data[$uuid]["balance"] = max(0, $this->data[$uuid]["balance"] - $amount);
            $this->save();
        }
    }

    public function setToken(Player|string $identifier, int $amount) : void{
        $uuid = $identifier instanceof Player ? $identifier->getUniqueId()->toString() : $identifier;
        if (isset($this->data[$uuid])) {
            $this->data[$uuid]["balance"] = $amount;
            $this->save();
        }
    }

    private function save() : void{
        file_put_contents($this->dataFile, json_encode($this->data, JSON_PRETTY_PRINT));
    }
}
