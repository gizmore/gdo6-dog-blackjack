<?php
declare(strict_types=1);
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

	protected function isRoomMethod(): bool { return false; }

	public function gdoParameters(): array
	{
		return [
			GDT_UInt::make('bet')->min(10)->max(1000000)->notNull(),
		];
	}

	public function dogExecute(DOG_Message $message, int $bet): bool
	{
		$game = Game::instance($message->user);
		if ($game->hasBet())
		{
			return $message->rply('err_bj_running');
		}
		if ($cards = $game->bet($bet))
		{
			$value = $game->handValue($cards);

			if ($value === 21)
			{
				$win = $game->won(true);
				return $message->rply('msg_bj_started_bj', [
					$bet, $win, $game->getCredits()]);
			}
			return $message->rply('msg_bj_started', [
				$bet, count($cards),
				$game->renderHand($cards), $game->getCredits()]);
		}
		return false;
	}

}
