<?php
declare(strict_types=1);
namespace GDO\DogBlackjack\Method;

use GDO\Dog\DOG_Command;
use GDO\Dog\DOG_Message;
use GDO\DogBlackjack\Game;
use GDO\DogBlackjack\Module_DogBlackjack;

/**
 * Reset coins to 100.
 *
 * @author gizmore
 */
final class Reset extends DOG_Command
{

	public function getCLITrigger(): string
	{
		return 'bj.reset';
	}

	public function dogExecute(DOG_Message $message): void
	{
		$user = $message->user->getGDOUser();
		$game = Game::instance($message->user);
		$game->over(true);
		Module_DogBlackjack::instance()->saveUserSetting($user, 'bj_coins', '100');
		$coins = $user->settingVar('DogBlackjack', 'bj_coins');
		$message->user->send(t('msg_bj_reset', [$coins]));
	}

}
