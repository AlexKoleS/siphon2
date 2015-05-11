<?php
namespace Icecave\Siphon\Schedule;

use Eloquent\Enumeration\AbstractEnumeration;

class ScheduleType extends AbstractEnumeration
{
    const FULL          = 'full';
    const LIMIT_2_DAYS  = 'limit_2_days';
    const LIMIT_7_DAYS  = 'limit_7_days';
    const LIMIT_30_DAYS = 'limit_30_days';
    const DELETED       = 'deleted';
}
