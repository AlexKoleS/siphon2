<?php
namespace Icecave\Siphon\Schedule;

use Eloquent\Enumeration\AbstractEnumeration;

class CompetitionStatus extends AbstractEnumeration
{
    const SCHEDULED      = 'scheduled';
    const IN_PROGRESS    = 'in-progress';
    const DELAY_RAIN     = 'delay-rain';
    const DELAY_OTHER    = 'delay-other';
    const DELAY_DARKNESS = 'delay-darkness';
    const SUSPENDED      = 'suspended';
    const POSTPONED      = 'postponed';
    const SHORTENED      = 'shortened';
    const CANCELLED      = 'cancelled';
    const OTHER          = 'other';
    const COMPLETE       = 'complete';
}
