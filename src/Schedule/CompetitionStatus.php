<?php
namespace Icecave\Siphon\Schedule;

use Eloquent\Enumeration\AbstractEnumeration;

class CompetitionStatus extends AbstractEnumeration
{
    const SCHEDULED   = 'scheduled';
    const SUSPENDED   = 'suspended';
    const IN_PROGRESS = 'in-progress';
    const COMPLETE    = 'complete';
}
