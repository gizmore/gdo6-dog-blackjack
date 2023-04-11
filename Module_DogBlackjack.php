<?php
declare(strict_types=1);
namespace GDO\DogBlackjack;

use GDO\Core\GDO_Module;
use GDO\Core\GDT_Int;
use GDO\Core\GDT_UInt;
use GDO\Dog\DOG_User;

/**
 * Blackjack cardgame for the dog chatbot.
 *
 * @version 7.0.3
 * @since 6.10.4
 * @author gizmore
 */
final class Module_DogBlackjack extends GDO_Module
{

	public function getDependencies(): array
	{
		return [
			'Dog',
		];
	}

	public function onLoadLanguage(): void
	{
		$this->loadLanguage('lang/blackjack');
	}

	public function getConfig(): array
	{
		return [
			GDT_UInt::make('min_bet')->initial('10'),
			GDT_UInt::make('bank_games')->initial('0'),
			GDT_UInt::make('bank_bj')->initial('0'),
			GDT_UInt::make('bank_won')->initial('0'),
			GDT_UInt::make('bank_lost')->initial('0'),
			GDT_Int::make('bank_coins')->initial('0'),
		];
	}

	public function cfgMinBet(): int
	{
		return $this->getConfigValue('min_bet');
	}

	public function getUserConfig(): array
	{
		return [
			GDT_UInt::make('bj_games')->initial('0'),
			GDT_UInt::make('bj_bj')->initial('0'),
			GDT_UInt::make('bj_won')->initial('0'),
			GDT_UInt::make('bj_lost')->initial('0'),
			GDT_UInt::make('bj_coins')->initial('100'),
			GDT_Int::make('bj_net')->initial('0'),
		];
	}

	public function getCredits(DOG_User $user): int
	{
		return $this->userSettingValue($user->getGDOUser(), 'bj_coins');
	}

	public function getNetTotal(): int
	{
		return $this->getConfigValue('bank_coins');
	}

	public function getNetUser(DOG_User $user): int
	{
		return $this->userSettingValue($user->getGDOUser(), 'bj_net');
	}

	public function getGamesTotal(): int
	{
		return $this->getConfigValue('bank_games');
	}

	public function getGamesUser(DOG_User $user): int
	{
		return $this->userSettingValue($user->getGDOUser(), 'bj_games');
	}

	public function getGamesWon(DOG_User $user): int
	{
		return $this->userSettingValue($user->getGDOUser(), 'bj_won');
	}

	public function getGamesLost(DOG_User $user): int
	{
		return $this->userSettingValue($user->getGDOUser(), 'bj_lost');
	}

	public function getGamesBJ(DOG_User $user): int
	{
		return $this->userSettingValue($user->getGDOUser(), 'bj_bj');
	}

	public function getBankGamesWon(): int
	{
		return $this->getConfigValue('bank_won');
	}

	public function getBankGamesLost(): int
	{
		return $this->getConfigValue('bank_lost');
	}

	public function getBankGamesBJ(): int
	{
		return $this->getConfigValue('bank_bj');
	}

	/**
	 * Update game statistics.
	 */
	public function saveGame(DOG_User $user, int $credits, bool $bj): bool
	{
		$usr = $user->getGDOUser();
		$this->increaseConfigVar('bank_games');
		$this->increaseConfigVar('bank_coins', -$credits);
		$this->increaseUserSetting($usr, 'bj_games');
		$this->increaseUserSetting($usr, 'bj_coins', $credits);
		$this->increaseUserSetting($usr, 'bj_net', $credits);
		if ($credits > 0)
		{
			$this->increaseUserSetting($usr, 'bj_won');
			$this->increaseUserSetting($usr, 'bj_bj', $bj ? 1 : 0);
			$this->increaseConfigVar('bank_lost');
		}
		else
		{
			$this->increaseConfigVar('bank_won');
			$this->increaseConfigVar('bank_bj', $bj ? 1 : 0);
			$this->increaseUserSetting($usr,'bj_lost');
		}
		return true;
	}

	public function bet(DOG_User $user, int $credits): bool
	{
		$this->increaseUserSetting($user->getGDOUser(), 'bj_coins', -$credits);
		return true;
	}

}
