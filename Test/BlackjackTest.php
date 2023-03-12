<?php
namespace GDO\DogBlackjack\Test;

use GDO\Dog\Test\DogTestCase;
use function PHPUnit\Framework\assertStringContainsString;
use function PHPUnit\Framework\assertStringContainsStringIgnoringCase;

final class BlackjackTest extends DogTestCase
{
    public function testBlackjack()
    {
        # Reset to 100 coins
        $this->dogUser('gizmore{1}');
        $r = $this->bashCommand('bj.reset');
        assertStringContainsStringIgnoringCase('100', $r, "Check if game can be reset.");
        assertStringContainsStringIgnoringCase('shuff', $r, "Check if game can be reshuffle resetted.");
        
        # Bet 50
        $this->dogUser('gizmore{1}');
        $r = $this->bashCommand('bj.bet 50');
        assertStringContainsString('50', $r, "Check if game can be started.");
    }
    
}
