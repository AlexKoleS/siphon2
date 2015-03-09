<?php
namespace Icecave\Siphon\Score\Innings;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * The innings sub-type.
 */
class InningsType extends AbstractEnumeration
{
    const TOP    = 'top';
    const BOTTOM = 'bottom';
}
