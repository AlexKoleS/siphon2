<?php
namespace Icecave\Siphon\LiveScore;

use Eloquent\Enumeration\AbstractEnumeration;

class ScopeType extends AbstractEnumeration
{
    const PERIOD   = 'period';
    const INNINGS  = 'innings';
    const OVERTIME = 'overtime';
    const SHOOTOUT = 'shootout';
}
