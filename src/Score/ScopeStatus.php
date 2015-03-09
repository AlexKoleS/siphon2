<?php
namespace Icecave\Siphon\Score;

use Eloquent\Enumeration\AbstractEnumeration;

class ScopeStatus extends AbstractEnumeration
{
    const IN_PROGRESS = 'in-progress';
    const COMPLETE    = 'complete';
}
