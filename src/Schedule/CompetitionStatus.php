<?php
namespace Icecave\Siphon\Schedule;

use Eloquent\Enumeration\AbstractEnumeration;

class CompetitionStatus extends AbstractEnumeration
{
    const SCHEDULED   = 'scheduled';
    const IN_PROGRESS = 'in-progress';
    const DELAY_RAIN  = 'delay-rain';
    const DELAY_OTHER = 'delay-other';
    const SUSPENDED   = 'suspended';
    const POSTPONED   = 'postponed';
    const COMPLETE    = 'complete';
}
