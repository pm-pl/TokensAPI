<?php

declare(strict_types=1);

namespace Terpz710\TokensAPI;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

use Terpz710\TokensAPI\API\TokenAPI;

class EventListener implements Listener {

  public function join(PlayerJoinEvent $event) : void{
      TokenAPI::getInstance()->createBalance($event->getPlayer());
  }
}
