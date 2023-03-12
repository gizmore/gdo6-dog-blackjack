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
    
    public function getCLITrigger()
    {
    	return 'bj.reset';
    }
    
    public function dogExecute(DOG_Message $message)
    {
        $user = $message->user->getGDOUser();
        $game = Game::instance($message->user);
        $game->over();
        Module_DogBlackjack::instance()->saveUserSetting($user, 'blackjack_coins', '100');
        $coins = $user->settingVar('DogBlackjack', 'blackjack_coins');
        $message->user->send(t('msg_bj_reset', [$coins]));
    }
    
}
