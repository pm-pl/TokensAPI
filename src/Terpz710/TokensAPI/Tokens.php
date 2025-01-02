<?php

declare(strict_types=1);

namespace Terpz710\TokensAPI;

use pocketmine\plugin\PluginBase;

use Terpz710\TokensAPI\API\TokenAPI;

use Terpz710\TokensAPI\Commands\PayTokens;
use Terpz710\TokensAPI\Commands\AddTokens;
use Terpz710\TokensAPI\Commands\RemoveTokens;
use Terpz710\TokensAPI\Commands\MyTokens;
use Terpz710\TokensAPI\Commands\SeeTokens;
use Terpz710\TokensAPI\Commands\TopTokens;
use Terpz710\TokensAPI\Commands\SetTokens;

final class Tokens extends PluginBase {

    private $tokenAPI;

    protected function onLoad() : void{
        self::$instance = $this;
    }

    protected function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->saveDefaultConfig();
        $this->registerCommands();
        $this->tokenAPI = new TokenAPI();
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
