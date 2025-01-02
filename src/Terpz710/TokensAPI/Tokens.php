<?php

declare(strict_types=1);

namespace Terpz710\TokensAPI;

use pocketmine\plugin\PluginBase;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

use Terpz710\TokensAPI\API\TokenAPI;

use Terpz710\TokensAPI\Commands\PayTokens;
use Terpz710\TokensAPI\Commands\AddTokens;
use Terpz710\TokensAPI\Commands\RemoveTokens;
use Terpz710\TokensAPI\Commands\MyTokens;
use Terpz710\TokensAPI\Commands\SeeTokens;
use Terpz710\TokensAPI\Commands\TopTokens;
use Terpz710\TokensAPI\Commands\SetTokens;

final class Tokens extends PluginBase implements Listener {

    private $tokenAPI;

    protected function onLoad() : void{
        self::$instance = $this;
    }

    protected function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
        $this->registerCommands();
        $this->tokenAPI = new TokenAPI();
    }

    public function join(PlayerJoinEvent $event) :void{
        $player = $event->getPlayer();
        $playerName = $player->getName();
        if (!$this->tokenAPI->getPlayerToken($player)) {
            $initialTokenAmount = $this->getConfig()->get("starting_token_amount");
            $this->tokenAPI->setToken($player, $initialTokenAmount);
        }
    }

    private function registerCommands() : void{
        $this->getServer()->getCommandMap()->registerAll("TokensAPI", [
            new PayTokens($this),
            new RemoveTokens($this),
	    new AddTokens($this),
	    new SeeTokens($this),
	    new MyTokens($this),
	    new TopTokens($this),
	    new SetTokens($this)
        ]);
    }

    public static function getInstance() : self{
        return self::$instance;
    }

    public function getTokenAPI() : TokenAPI{
        return $this->tokenAPI;
    }
}
