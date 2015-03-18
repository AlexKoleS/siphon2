<?php
namespace Icecave\Siphon\Score\LiveScore;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * A sub-type (half) of an inning.
 */
class InningSubType extends AbstractEnumeration
{
    const TOP    = 'top';    // away team is batting
    const BOTTOM = 'bottom'; // home team is batting
}
