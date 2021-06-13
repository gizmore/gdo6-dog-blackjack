<?php
namespace GDO\DogBlackjack\Method;

use GDO\Dog\DOG_Command;
use GDO\Dog\DOG_Message;
use GDO\DogBlackjack\Game;

/**
 * Draw another card.
 * @author gizmore
 */
final class Draw extends DOG_Command
{
    public $group = 'BJ';
    public $trigger = 'draw';
    
    public function dogExecute(DOG_Message $message)
    {
        $game = Game::instance($message->user);
        $game->draw(1);
    }
    
}
