<?php
namespace Icecave\Siphon\LiveScore;

use Eloquent\Enumeration\AbstractEnumeration;

class ScopeType extends AbstractEnumeration
{
    const PERIOD         = 'period';
    const OVERTIME       = 'overtime';
    const SHOOTOUT       = 'shootout';
    const INNINGS_TOP    = 'innings_top';
    const INNINGS_BOTTOM = 'innings_bottom';
}
