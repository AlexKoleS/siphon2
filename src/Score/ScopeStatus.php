<?php
namespace Icecave\Siphon\Score;

use Eloquent\Enumeration\AbstractEnumeration;

class ScopeStatus extends AbstractEnumeration
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
