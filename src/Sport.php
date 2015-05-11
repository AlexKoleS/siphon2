<?php
namespace Icecave\Siphon;

use Eloquent\Enumeration\AbstractMultiton;

final class Sport extends AbstractMultiton
{
    protected function __construct($key, $sport)
    {
        parent::__construct($key);

        $this->sport = $sport;
    }

    protected static function initializeMembers()
    {
        new static('NFL',   'football');
        new static('NCAAF', 'football');
        new static('NBA',   'basketball');
        new static('NCAAB', 'basketball');
        new static('MLB',   'baseball');
        new static('NHL',   'hockey');
    }

    public static function fromComponents($sport, $league)
    {
        return self::memberByPredicate(
            function ($member) use ($sport, $league) {
                return $member->sport === $sport
                    && $member->key() === $league;
            }
        );
    }

    public function sport()
    {
        return $this->sport;
    }

    public function league()
    {
        return $this->key();
    }

    private $sport;
}
