<?php
namespace Icecave\Siphon;

use Eloquent\Enumeration\AbstractMultiton;

/**
 * A unique sport + league.
 */
final class Sport extends AbstractMultiton
{
    /**
     * @codeCoverageIgnore Multitons are initialized before the test suites are executed.
     */
    protected function __construct($key, $sport)
    {
        parent::__construct($key);

        $this->sport = $sport;
    }

    /**
     * @codeCoverageIgnore Multitons are initialized before the test suites are executed.
     */
    protected static function initializeMembers()
    {
        new static('NFL',   'football');
        new static('NCAAF', 'football');
        new static('NBA',   'basketball');
        new static('NCAAB', 'basketball');
        new static('MLB',   'baseball');
        new static('NHL',   'hockey');
    }

    /**
     * Find a member by its components.
     *
     * @param string $sport  The sport (e.g. 'football', 'baseball').
     * @param string $league The league (e.g. 'NFL', 'MLB').
     */
    public static function findByComponents($sport, $league)
    {
        return self::memberByPredicate(
            function ($member) use ($sport, $league) {
                return $member->sport === strtolower($sport)
                    && $member->key() === strtoupper($league);
            }
        );
    }

    /**
     * Get the sport.
     *
     * @return string The sport (e.g. 'football', 'baseball').
     */
    public function sport()
    {
        return $this->sport;
    }

    /**
     * Get the league.
     *
     * @return string The league (e.g. 'NFL', 'MLB').
     */
    public function league()
    {
        return $this->key();
    }

    private $sport;
}
