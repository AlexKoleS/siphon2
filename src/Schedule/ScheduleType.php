<?php

namespace Icecave\Siphon\Schedule;

use Eloquent\Enumeration\AbstractEnumeration;

class ScheduleType extends AbstractEnumeration
{
    const FULL          = INF;
    const LIMIT_2_DAYS  = 2;
    const LIMIT_7_DAYS  = 7;
    const LIMIT_30_DAYS = 30;
    const DELETED       = 0;
}
