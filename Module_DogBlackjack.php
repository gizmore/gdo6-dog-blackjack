<?php
namespace GDO\DogBlackjack;

use GDO\Core\GDO_Module;
use GDO\DB\GDT_UInt;

/**
 * Blackjack cardgame for the dog chatbot.
 * @author gizmore
 * @version 6.10.4
 * @since 6.10.4
 */
final class Module_DogBlackjack extends GDO_Module
{
    public function onLoadLanguage() { return $this->loadLanguage('lang/blackjack'); }
    
    public function getConfig()
    {
        return [
            GDT_UInt::make('total_games')->initial('0'),
        ];
    }
    
    public function getUserConfig()
    {
        return [
            GDT_UInt::make('blackjack_games')->initial('0'),
            GDT_UInt::make('blackjack_coins')->initial('100'),
        ];
    }

}
