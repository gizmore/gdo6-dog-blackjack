<?php
namespace GDO\DogBlackjack\Method;

use GDO\Dog\DOG_Command;
use GDO\Dog\DOG_Message;
use GDO\Dog\DOG_User;
use GDO\Dog\GDT_DogUser;

/**
 * @author gizmore
 */
final class Stats extends DOG_Command
{

	public $group = 'BJ';
	public $trigger = 'stats';

	public function getCLITrigger()
	{
		return 'bj.stats';
	}

	public function isRoomMethod() { return true; }

	public function gdoParameters(): array
	{
		return [
			GDT_DogUser::make('user'),
		];
	}

	public function dogExecute(DOG_Message $message, DOG_User $user = null)
	{
		if ($user)
		{
			$this->userstats($message, $user);
		}
		else
		{
			$this->globalStats($message);
		}
		return $message->rply('msg_bj_started', [$bet]);
	}

}
