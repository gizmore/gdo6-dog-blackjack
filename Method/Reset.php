<?php
namespace GDO\DogBlackjack\Method;

use GDO\Dog\DOG_Command;
use GDO\Dog\DOG_Message;
use GDO\DogBlackjack\Game;
use GDO\DogBlackjack\Module_DogBlackjack;

/**
 * Reset coins to 100.
 * @author gizmore
 */
final class Reset extends DOG_Command
{
    public $group = 'BJ';
    public $trigger = 'reset';
    
    public function dogExecute(DOG_Message $message)
    {
        $user = $message->user->getGDOUser();
        $game = Game::instance($message->user);
        $game->over();
        Module_DogBlackjack::instance()->saveUserSetting($user, 'blackjack_coins', '100');
    }
    
}
