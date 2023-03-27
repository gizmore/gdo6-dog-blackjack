<?php
namespace GDO\DogBlackjack\Method;

use GDO\Core\GDT_UInt;
use GDO\Dog\DOG_Command;
use GDO\Dog\DOG_Message;
use GDO\DogBlackjack\Game;

/**
 * Initiate a game.
 *
 * @author gizmore
 */
final class Bet extends DOG_Command
{

	public function getCLITrigger(): string
	{
		return 'bj.bet';
	}

	public function isRoomMethod() { return false; }

	public function gdoParameters(): array
	{
		return [
			GDT_UInt::make('bet')->min(10)->max(1000000)->notNull(),
		];
	}

	public function dogExecute(DOG_Message $message, $bet)
	{
		$game = Game::instance($message->user);
		if ($game->hasBet())
		{
			return $message->rply('err_bj_running');
		}
		$game->bet($bet);
		return $message->rply('msg_bj_started', [$bet]);
	}

}
