<?php
namespace GDO\DogBlackjack\Method;

use GDO\Dog\DOG_Command;
use GDO\Dog\DOG_Message;
use GDO\DogBlackjack\Game;

/**
 * Hold and let the dealer draw.
 * @author gizmore
 */
final class Hold extends DOG_Command
{
    public $group = 'BJ';
    public $trigger = 'hold';
    
    public function dogExecute(DOG_Message $message)
    {
        $game = Game::instance($message->user);
        
    }

}
