<?php
declare(strict_types=1);
namespace GDO\DogBlackjack\Method;

use GDO\Dog\DOG_Command;
use GDO\Dog\DOG_Message;
use GDO\Dog\DOG_User;
use GDO\Dog\GDT_DogUser;
use GDO\DogBlackjack\Module_DogBlackjack;
use GDO\User\GDO_User;
use GDO\User\GDO_UserSetting;

/**
 * BlackJack Statistics.
 *
 * @author gizmore
 */
final class Stats extends DOG_Command
{

	public function getCLITrigger(): string
	{
		return 'bj.stats';
	}

	protected function isRoomMethod(): bool { return true; }

	public function gdoParameters(): array
	{
		return [
			GDT_DogUser::make('user'),
		];
	}

	public function dogExecute(DOG_Message $message, DOG_User $user = null): bool
	{
		if ($user)
		{
			return $this->userstats($message, $user);
		}
		else
		{
			return $this->globalStats($message);
		}
	}

	private function userstats(DOG_Message $message, DOG_User $user): bool
	{
		$m = Module_DogBlackjack::instance();
		return $message->rply('msg_blackjack_user_stats', [
			$m->getGamesUser($user),
			$m->getGamesWon($user),
			$m->getGamesLost($user),
			$m->getGamesBJ($user),
			$m->getCredits($user),
			$m->getNetUser($user),
		]);
	}

	private function globalStats(DOG_Message $message): bool
	{
		$m = Module_DogBlackjack::instance();
		$message->rply('msg_blackjack_global_stats', [
			$m->getGamesTotal(),
			$m->getBankGamesWon(),
			$m->getBankGamesLost(),
			$m->getBankGamesBJ(),
			$m->getNetTotal(),
		]);
		return $this->bestPlayerStats($message);
	}

	private function bestPlayerStats(DOG_Message $message): bool
	{
		$m = Module_DogBlackjack::instance();
		if ($players = $this->getBestPlayers())
		{
			$rank = 1;
			$out = [];
			foreach ($players as $player)
			{
				$user = DOG_User::getFor($player);
				$out[] = sprintf('%s: %s %s(%s)',
					$rank++,
					$player->renderUserName(),
					$m->getCredits($user),
					$m->getNetUser($user));
			}
			return $message->rply('msg_blackjack_global_bests', [
				count($players),
				implode(', ', $out),
			]);
		}
		return true;
	}

	/**
	 * @return GDO_User[]
	 */
	private function getBestPlayers(): array
	{
		$q = GDO_UserSetting::usersWithQuery('DOGBlackjack', 'bj_coins', '0', '>');
		$q->order('uset_var DESC');
		$q->fetchTable(GDO_User::table());
		$q->limit(5);
		return $q->exec()->fetchAllObjects();
	}

}
