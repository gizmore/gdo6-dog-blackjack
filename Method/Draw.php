<?php
declare(strict_types=1);
namespace GDO\DogBlackjack\Method;

use GDO\Core\GDT_UInt;
use GDO\Dog\DOG_Command;
use GDO\Dog\DOG_Message;
use GDO\DogBlackjack\Game;

/**
 * Draw more cards.
 *
 * @author gizmore
 * @version 7.0.3
 */
final class Draw extends DOG_Command
{

	public function getCLITrigger(): string
	{
		return 'bj.draw';
	}

	public function gdoParameters(): array
	{
		return [
			GDT_UInt::make('amount')->min(1)->max(4)->initial('1')->positional(),
		];
	}

	public function dogExecute(DOG_Message $message, int $amount): bool
	{
		$game = Game::instance($message->user);
		if (!$game->hasBet())
		{
			return $message->rply('err_blackjack_no_game');
		}
		$cards = $game->draw($amount);
		$value = $game->handValue($cards);
		if ($value > 21)
		{
			$loss = $game->lost();
			return $message->rply('msg_blackjack_draw_busted', [
				$amount, $game->renderHand($cards), $value, $loss, $game->getCredits()]);
		}
		elseif ($value === 21)
		{
			$win = $game->won(true);
			return $message->rply('msg_blackjack_draw_bj', [
				$amount, $game->renderHand($cards), $win, $game->getCredits()]);
		}
		else
		{
			return $message->rply('msg_blackjack_draw', [
				$amount, $game->renderHand($cards)]);
		}
	}

}
