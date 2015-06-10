<?php
namespace Icecave\Siphon\Score;

use Eloquent\Enumeration\AbstractEnumeration;
use Icecave\Siphon\Sport;
use InvalidArgumentException;

class PeriodType extends AbstractEnumeration
{
    const QUARTER      = 'quarter';      // NFL, NCAAF, NBA
    const HALF         = 'half';         // NCAAB
    const PERIOD       = 'period';       // NHL
    const OVERTIME     = 'overtime';     // NFL, NCAAF, NBA, NCAAB, NHL
    const SHOOTOUT     = 'shootout';     // NHL
    const INNING       = 'inning';       // MLB
    const EXTRA_INNING = 'extra-inning'; // MLB

    /**
     * Returns a single member by value.
     *
     * @param Sport        $sport           The sport.
     * @param scalar       $value           The value associated with the member.
     * @param boolean|null $isCaseSensitive True if the search should be case sensitive.
     *
     * @return ValueMultitonInterface             The first member with the supplied value.
     * @throws Exception\UndefinedMemberException If no associated member is found.
     */
    final public static function memberBySportAndValue(Sport $sport, $value, $isCaseSensitive = null)
    {
        $member = static::memberByValue($value, $isCaseSensitive);

        if ($member->usedBy($sport)) {
            return $member;
        }

        throw new InvalidArgumentException(
            $sport . ' does not use the ' . $member . ' period type.'
        );
    }

    /**
     * Indicates whether or not this period type is used by the given sport.
     *
     * @param Sport $sport
     *
     * @return boolean
     */
    public function usedBy(Sport $sport)
    {
        if (Sport::NCAAB() === $sport) {
            return $this->anyOf(
                self::HALF(),
                self::OVERTIME()
            );
        } elseif (Sport::NHL() === $sport) {
            return $this->anyOf(
                self::PERIOD(),
                self::OVERTIME(),
                self::SHOOTOUT()
            );
        } elseif (Sport::MLB() === $sport) {
            return $this->anyOf(
                self::INNING(),
                self::EXTRA_INNING()
            );
        }

        return $this->anyOf(
            self::QUARTER(),
            self::OVERTIME()
        );
    }
}
