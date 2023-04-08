<?php
declare(strict_types=1);
namespace GDO\DogBlackjack\lang;
return [
	'module_dogblackjack' => 'Dog(BlackJack)',

	'bj_hand' => 'Now holding %s cards: %s (%s points)',
	'bj_games' => 'Games',
	'bj_bj' => 'BlackJacks!',
	'bj_net' => 'Credits accumulated',
	'bj_won' => 'Credits won',
	'bj_lost' => 'Credits lost',
	'bj_coins' => 'Credits now',

	'cfg_min_bet' => 'Minimum bet',
	'cfg_bank_bj' => 'Bank BlackJack! counter',
	'cfg_bank_coins' => 'Bank Net Worth',
	'cfg_bank_games' => 'Total games played',
	'cfg_bank_lost' => 'Total bank loss',
	'cfg_bank_won' => 'Total bank win',

	'mt_dogblackjack_reset' => 'Reset your blackjack statistics.',
	'err_bj_running' => 'There is already a game running. Try bj.draw or bj.hold.',
	'msg_bj_shuffle' => 'The dealer is shuffeling the cards...',
	'msg_bj_reset' => 'The game has been reset. Your coins: %s.',

	'mt_dogblackjack_bet' => 'Bet money on the next cards.',
	'bet' => 'Your Bet',
	'err_blackjack_min' => 'You have to bet at least %s credits. You currenlty have %s. You can bj.reset to start over.',
	'err_blackjack_money' => 'You cannot bet %s credits, as you only have %s.',
	'err_blackjack_not_over' => 'You currently have these cards: %s.',
	'msg_bj_started' => 'You bet %s credits and draw %s cards. %s. You have %s credits left.',
	'msg_bj_started_bj' => 'You bet %s credits, draw 2 cards... and BlackJack! You win %s credits and now have %s.',

	'mt_dogblackjack_draw' => 'Draw cards from the dealer.',
	'err_blackjack_no_game' => 'There is no blackjack game running for you.',
	'msg_blackjack_draw' => 'You draw %s cards. %s.',
	'msg_blackjack_draw_bj' => 'You draw %s cards. %s. BlackJack! You win %s credits and now have %s.',
	'msg_blackjack_draw_busted' => 'You draw %s cards. %s. Busted with %s points. You lost your bet of %s credits and now have %s.',

	'mt_dogblackjack_hold' => 'Hold your cards and let the dealer play.',
	'msg_blackjack_dealer_won' => 'The dealer takes %s cards. %s. You lost %s credits and now have %s.',
	'msg_blackjack_dealer_lost' => 'The dealer takes %s cards. %s. You win %s credits and now have %s.',

	'mt_dogblackjack_stats' => 'Blackjack Player and Global statistics.',
	'msg_blackjack_user_stats' => '%s has played %s games. %s won / %s loss / %s BJ! Credits: %s; Net: %s.',
	'msg_blackjack_global_stats' => 'The bank has played %s games. %s won / %s loss / %s BJ! Net Worth: %s.',
	'msg_blackjack_global_bests' => 'The %s best players: %s.',

];
