<?php

namespace Icecave\Siphon\Team;

use Countable;
use Icecave\Siphon\Reader\ResponseInterface;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use IteratorAggregate;

/**
 * The response from a team feed.
 */
class TeamResponse implements
    ResponseInterface,
    Countable,
    IteratorAggregate
{
    use TeamResponseTrait;

    /**
     * Add a team to the response.
     *
     * @param TeamInterface $team The team to add.
     */
    public function add(TeamInterface $team)
    {
        $this->entries[$team->id()] = $team;
    }

    /**
     * Dispatch a call to the given visitor.
     *
     * @param ResponseVisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(ResponseVisitorInterface $visitor)
    {
        return $visitor->visitTeamResponse($this);
    }
}
