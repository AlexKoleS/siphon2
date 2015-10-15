<?php

namespace Icecave\Siphon\Player;

use Icecave\Siphon\Reader\RequestVisitorInterface;
use Icecave\Siphon\Reader\SportRequestInterface;

/**
 * A request to the player feed.
 */
class PlayerRequest implements SportRequestInterface
{
    use PlayerRequestTrait;

    /**
     * Dispatch a call to the given visitor.
     *
     * @param RequestVisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(RequestVisitorInterface $visitor)
    {
        return $visitor->visitPlayerRequest($this);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            'player(%s %s team:%d)',
            $this->sport->key(),
            $this->seasonName,
            $this->teamId
        );
    }
}
