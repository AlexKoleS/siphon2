<?php

namespace Icecave\Siphon\Statistics;

use Eloquent\Enumeration\AbstractEnumeration;

class StatisticsType extends AbstractEnumeration
{
    const COMBINED = 'combined';
    const SPLIT    = 'split';
}
