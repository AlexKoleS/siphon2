<?php
namespace Icecave\Siphon\Reader;

use Icecave\Siphon\Atom\AtomRequest;
use Icecave\Siphon\Player\PlayerRequest;
use Icecave\Siphon\Schedule\ScheduleRequest;
use Icecave\Siphon\Team\TeamRequest;

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

    /**
     * Visit the given request.
     *
     * @param TeamRequest $request
     *
     * @return mixed
     */
    public function visitTeamRequest(TeamRequest $request);

    /**
     * Visit the given request.
     *
     * @param PlayerRequest $request
     *
     * @return mixed
     */
    public function visitPlayerRequest(PlayerRequest $request);
}
