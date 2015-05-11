<?php
namespace Icecave\Siphon\Schedule;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * A sporting schedule.
 */
class ScheduleLimit extends AbstractEnumeration
{
    const NONE    = null;
    const DAYS_2  = 2;
    const DAYS_7  = 7;
    const DAYS_30 = 30;
}
