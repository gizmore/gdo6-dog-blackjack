<?php
declare(strict_types=1);
namespace GDO\DogBlackjack\Test;

use GDO\Dog\Test\DogTestCase;
use GDO\DogBlackjack\Game;
use GDO\DogBlackjack\Module_DogBlackjack;
use function PHPUnit\Framework\assertStringContainsString;
use function PHPUnit\Framework\assertStringContainsStringIgnoringCase;

/**
 * Test BlackJack via Bash connector.
 * Should work fine in IRC and other connectors.
 *
 * @version 7.0.3
 */
final class BlackjackTest extends DogTestCase
{

	public function testHands(): void
	{
		$this->userGizmore1();
		$game = Game::instance($this->doguser);
		assertStringContainsString('20', $game->renderHand(['A', '9']), 'Test if BJ card combi A9 works.');
		assertStringContainsString('21', $game->renderHand(['A', '10']), 'Test if BJ card combi A10 works.');
		assertStringContainsString('21', $game->renderHand(['A', 'A', 'A', 'A', 'A']), 'Test if BJ card combi AAAAA works.');
		assertStringContainsString('16', $game->renderHand(['3', '5', '8']), 'Test if BJ card combi 358 works.');
	}


	public function testBlackjack(): void
	{
		# Reset to 100 coins
		$this->userGizmore1();
		$r = $this->bashCommand('bj.reset');
		assertStringContainsStringIgnoringCase('100', $r, 'Check if game can be reset.');
		assertStringContainsStringIgnoringCase('reset', $r, 'Check if game can be resetted.');
		assertStringContainsStringIgnoringCase('shuff', $r, 'Check if game can be reshuffled.');

		# No game
		$r = $this->bashCommand('bj.draw');
		assertStringContainsString('There is no blackjack', $r, 'Check if card can be drawn.');

		# Bet 25
		$r = $this->bashCommand('bj.bet 25');
		assertStringContainsString('25', $r, 'Check if game can be started.');
		assertStringContainsString('75', $r, 'Check if game bet accounts correctly.');
		assertStringContainsString('2 cards', $r, 'Check if game deals 2 cards in beginning.');

		$r = $this->bashCommand('bj.draw');
		$this->assertOneStringContained(['draw 1 card', 'no black'], $r, 'Check if 1 card can be drawn.');

		$this->bashCommand('bj.reset');
		$this->bashCommand('bj.bet 25');
		$r = $this->bashCommand('bj.draw 4');
		$this->assertOneStringContained(['Busted', 'no black'], $r, 'Check if 4 cards drawn at once or game over.');

		$this->bashCommand('bj.reset');
		$r = $this->bashCommand('bj.bet -10');
		assertStringContainsString('between', $r, 'Check if negative bet is blocked.');
		$r = $this->bashCommand('bj.bet 10');
		assertStringContainsString('2 cards', $r, 'Check if bet 10 works.');
		$r = $this->bashCommand('bj.hold');
		assertStringContainsString('dealer', $r, 'Check if dealer plays.');

		$r = $this->bashCommand('bj.stats');
		assertStringContainsString('games', $r, 'Check if global stats work.');
		assertStringContainsString('best', $r, 'Check if best player stats work.');

		$r = $this->bashCommand('bj.stats giz');
		assertStringContainsString('gizmore{1}', $r, 'Check if player stats work.');

		$m = Module_DogBlackjack::instance();
		self::assertGreaterThanOrEqual(2, $m->getGamesTotal(), 'Test if at least 2 games were played.');
	}


}
