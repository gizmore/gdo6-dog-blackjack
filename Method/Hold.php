<?php
declare(strict_types=1);
namespace GDO\DogBlackjack\Method;

use GDO\Dog\DOG_Command;
use GDO\Dog\DOG_Message;
use GDO\DogBlackjack\Game;

/**
 * Hold and let the dealer draw.
 *
 * @version 7.0.3
 * @author gizmore
 */
final class Hold extends DOG_Command
{

	public function getCLITrigger(): string
	{
		return 'bj.hold';
	}

	public function dogExecute(DOG_Message $message): bool
	{
		$game = Game::instance($message->user);
		if ($game->running())
		{
			$bj = false;
			$cards = [];
			if ($this->letDealerPlay($game, $cards, $bj))
			{
				return $this->lost($game, $cards, $bj);
			}
			else
			{
				return $this->won($game, $cards, $bj);
			}
		}
		return false;
	}

	private function letDealerPlay(Game $game, array &$cards, bool &$bj=null): bool
	{
		$yours = $game->playerValue();
		$cards = [$game->drawCard()];
		$dealer = 0;
		while ($dealer < $yours)
		{
			$cards[] = $game->drawCard();
			$dealer = $game->handValue($cards, $bj);
		}
		return $dealer <= 21;
	}

	private function won(Game $game, array $cards, bool $bj): bool
	{
		$game->won($bj);
		return $game->rply('msg_blackjack_dealer_lost', [
			count($cards),
			$game->renderHand($cards),
			$game->getBet() * 2,
			$game->getCredits()]);
	}

	private function lost(Game $game, array $cards, bool $bj): bool
	{
		$game->lost($bj);
		return $game->rply('msg_blackjack_dealer_won', [
			count($cards),
			$game->renderHand($cards),
			$game->getBet(), $game->getCredits()]);
	}


}
