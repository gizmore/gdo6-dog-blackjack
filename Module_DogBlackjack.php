<?php
declare(strict_types=1);
namespace GDO\DogBlackjack;

use GDO\Core\GDO_Module;
use GDO\Core\GDT_UInt;

/**
 * Blackjack cardgame for the dog chatbot.
 *
 * @version 7.0.3
 * @since 6.10.4
 * @author gizmore
 */
final class Module_DogBlackjack extends GDO_Module
{

	public function onLoadLanguage(): void { $this->loadLanguage('lang/blackjack'); }

	public function getConfig(): array
	{
		return [
			GDT_UInt::make('total_games')->initial('0'),
		];
	}

	public function getUserConfig(): array
	{
		return [
			GDT_UInt::make('blackjack_games')->initial('0'),
			GDT_UInt::make('blackjack_coins')->initial('100'),
		];
	}

}
