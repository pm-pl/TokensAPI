<?php

declare(strict_types=1);

namespace Terpz710\TokensAPI;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

use Terpz710\TokensAPI\API\TokenAPI;

class EventListener implements Listener {

  public function join(PlayerJoinEvent $event) :void{
        $player = $event->getPlayer();
        $api = TokenAPI::getInstance();
    
        if (!$api->getTokenBalance($player)) {
            $initialTokenAmount = Tokens::getInstance()->getConfig()->get("starting_token_amount");
            $api->setToken($player, $initialTokenAmount);
        }
    }
}
