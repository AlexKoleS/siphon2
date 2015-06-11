<?php
namespace Icecave\Siphon\Score;

use Eloquent\Enumeration\AbstractEnumeration;
use Eloquent\Enumeration\Exception\UndefinedMemberException;
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
     * @param string       $value           The value associated with the member.
     * @param boolean|null $isCaseSensitive True if the search should be case sensitive.
     *
     * @return PeriodType               The first member with the supplied value.
     * @throws UndefinedMemberException If no associated member is found.
     */
    public static function memberBySportAndValue(Sport $sport, $value, $isCaseSensitive = null)
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
     * Returns a single member by code.
     *
     * @param string $value The value associated with the member.
     *
     * @return PeriodType               The member with the supplied code.
     * @throws UndefinedMemberException If no associated member is found.
     */
    public static function memberByCode($code)
    {
        foreach (self::members() as $member) {
            if ($member->code() === $code) {
                return $member;
            }
        }

        throw new UndefinedMemberException(
            __CLASS__,
            'code',
            $code
        );
    }

    /**
     * Get the single-character code for this period type.
     */
    public function code()
    {
        return $this->key()[0];
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
