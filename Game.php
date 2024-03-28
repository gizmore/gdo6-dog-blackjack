<?php
declare(strict_types=1);
namespace GDO\DogBlackjack;

use GDO\Core\GDT;
use GDO\Dog\DOG_Message;
use GDO\Dog\DOG_User;
use GDO\Util\Permutations;

/**
 * Blackjack game implementation for the gdo6-dog chatbot.
 *
 * In memory of Sheep.
 *
 * @version 7.0.3
 * @author gizmore
 */
final class Game
{

	###############
	### Factory ###
	###############
	public static array $GAMES = [];

	private int $bet = 0;

	###
	private DOG_User $user;
	private array $cards = [];
	private array $hand = [];

	public function __construct(DOG_User $user)
	{
		$this->user = $user;
		$this->shuffle();
	}

	public function rply(string $key, array $args=null): GDT
	{
		return DOG_Message::$LAST_MESSAGE->rply($key, $args);
	}

	private function shuffle(): void
	{
		$this->user->send(t('msg_bj_shuffle'));
		$cards = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];
		$this->cards = array_merge(
			$cards, $cards, $cards, $cards,
			$cards, $cards, $cards, $cards);
		shuffle($this->cards);
	}

	public static function instance(DOG_User $user): Game
	{
		$uid = $user->getID();
		if (!isset(self::$GAMES[$uid]))
		{
			self::$GAMES[$uid] = new self($user);
		}
		return self::$GAMES[$uid];
	}


	public function hasBet(): bool
	{
		return $this->bet > 0;
	}

	public function bet(int $bet): ?array
	{
		$m = Module_DogBlackjack::instance();
		$min = $m->cfgMinBet();
		$have = $m->getCredits($this->user);
		if ($bet < $min)
		{
			$this->rply('err_blackjack_min', [$bet, $have]);
		}
		elseif ($have < $bet)
		{
			$this->rply('err_blackjack_money', [$bet, $have]);
		}
		else
		{
			$m->bet($this->user, $bet);
			$this->bet = $bet;
			$this->hand = [];
			return $this->draw(2);
		}
		return null;
	}

	public function draw(int $amt=1): array
	{
		for ($i = 0; $i < $amt; $i++)
		{
			$this->hand[] = $this->drawCard();
		}
		return $this->hand;
	}

	public function drawCard(): string
	{
		return array_pop($this->cards);
	}

	public function over(bool $shuffle=false): bool
	{
		$this->bet = 0;
		$this->hand = [];
		if ( ($shuffle) || (count($this->cards) < 32) )
		{
			$this->shuffle();
		}
		return true;
	}

	public function handBusted(array $cards): bool
	{
		return $this->handValue($cards) > 21;
	}

	/**
	 * Compute the BJ hand strength.
	 * @param string[] $cards
	 */
	public function handValue(array $cards, bool &$bj=null): int
	{
		$bj = false;
		$perms = [];
		foreach ($cards as $card)
		{
			$perms[] = $this->cardValue($card);
		}
		$min = 137;
		$max = 0;
		$perms = new Permutations($perms);
		foreach ($perms->generate() as $p)
		{
			$sum = array_sum($p);
			if ($sum == 21)
			{
				$bj = true;
				return 21;
			}
			elseif ($sum < 21)
			{
				$max = max($sum, $max);
			}
			$min = min($sum, $min);
		}
		if ( (count($cards) >= 5) && ($min <= 21))
		{
			$bj = true;
			return 21;
		}
		if ($min > 21)
		{
			return $min;
		}
		return $max;
	}

	/**
	 * @return int[]
	 */
	private function cardValue(string $card): array
	{
		switch ($card)
		{
			case '10':
			case 'J':case 'Q':case 'K':
				return [10];
			case 'A':
				return [11, 1];
			default:
				return [(int)$card];
		}
	}

	public function running(): bool
	{
		if ($this->hasBet())
		{
			return true;
		}
		$this->rply('err_blackjack_no_game');
		return false;
	}

	public function playerValue(): int
	{
		return $this->handValue($this->hand);
	}

	public function getCredits(): int
	{
		return Module_DogBlackjack::instance()->getCredits($this->user);
	}

	public function lost(bool $bj = false): int
	{
		$m = Module_DogBlackjack::instance();
		$m->saveGame($this->user, -$this->bet, $bj);
		$loss = $this->bet;
		$this->over();
		return $loss;
	}

	public function won(bool $bj=false): int
	{
		$m = Module_DogBlackjack::instance();
		$win = $this->bet * ($bj ? 4 : 2);
		$m->saveGame($this->user, $win, $bj);
		$this->over();
		return $win;
	}

	public function renderHand(array $cards): string
	{
		return t('bj_hand', [
			count($cards),
			implode(', ', $cards),
			$this->handValue($cards)]);
	}

	public function getBet(): int
	{
		return $this->bet;
	}

}
