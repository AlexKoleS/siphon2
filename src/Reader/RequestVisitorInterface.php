<?php
namespace Icecave\Siphon\Reader;

use Icecave\Siphon\Atom\AtomRequest;
use Icecave\Siphon\Schedule\ScheduleRequest;

/**
 * Request visitor.
 */
interface RequestVisitorInterface
{
    /**
     * Visit the given request.
     *
     * @param AtomRequest $request
     *
     * @return mixed
     */
    public function visitAtomRequest(AtomRequest $request);

    /**
     * Visit the given request.
     *
     * @param ScheduleRequest $request
     *
     * @return mixed
     */
    public function visitScheduleRequest(ScheduleRequest $request);
}
