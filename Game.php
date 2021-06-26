<?php
namespace GDO\DogBlackjack;

use GDO\Dog\DOG_User;

/**
 * Blackjack game implementation for the gdo6-dog chatbot.
 * 
 * In memory of Sheep.
 * @author gizmore
 * @version 6.10.4
 */
final class Game
{
    ###############
    ### Factory ###
    ###############
    public static $GAMES = [];
    
    /**
     * @param DOG_User $user
     * @return self
     */
    public static function instance(DOG_User $user)
    {
        if (!isset(self::$GAMES[$user->getID()]))
        {
            self::$GAMES[$user->getID()] = new self($user);
        }
        return self::$GAMES[$user->getID()];
    }
    
    ### 
    
    private $bet = 0;
    private $user;
    private $cards;
    private $hand;
    
    public function __construct(DOG_User $user)
    {
        $this->user = $user;
        $this->shuffle();
    }
    
    private function shuffle()
    {
        $this->user->send(t('msg_bj_shuffle'));
        $cards = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];
        $this->cards = array_merge($cards, $cards, $cards, $cards, 
            $cards, $cards, $cards, $cards);
        shuffle($this->cards);
    }
    
    public function value()
    {
        
    }
    
    public function hasBet()
    {
        return $this->bet > 0;
    }
    
    public function bet($bet)
    {
        $this->bet = $bet;
        $this->hand = [];
        $this->draw(2);
    }
    
    public function draw($amt)
    {
        for ($i = 0; $i < $amt; $i++)
        {
            $this->hand[] = array_pop($this->cards);
        }

        $hand = implode(', ', $this->hand);
        
        $this->user->send(t('msg_blackjack_draw', [$amt, $hand]));
    }
    
    public function over()
    {
        $this->bet = 0;
        $this->hand = [];
        $this->shuffle();
    }
    
    public function handValue()
    {
        return $this->handValueFor($this->hand);
    }
    
    public function handValueFor($hand)
    {
        
        
    }
    
    public function handBusted()
    {
        if ($this->handValue() > 21)
        {
            
        }
        
    }
    
}
