<?php
namespace Icecave\Siphon\Score;

use Eloquent\Enumeration\AbstractEnumeration;

class PeriodType extends AbstractEnumeration
{
    const PERIOD   = 'period';
    const OVERTIME = 'overtime';
    const SHOOTOUT = 'shootout';
}
