<?php
namespace Icecave\Siphon\Score;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * The inning half.
 */
class InningHalf extends AbstractEnumeration
{
    const TOP    = 'top';    // away team is batting
    const BOTTOM = 'bottom'; // home team is batting
}
