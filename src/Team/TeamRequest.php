<?php
namespace Icecave\Siphon\Team;

use Icecave\Siphon\Reader\RequestVisitorInterface;
use Icecave\Siphon\Reader\SportRequestInterface;
use Icecave\Siphon\Sport;

/**
 * A request to the team feed.
 */
class TeamRequest implements SportRequestInterface
{
    use TeamRequestTrait;

    /**
     * Dispatch a call to the given visitor.
     *
     * @param RequestVisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(RequestVisitorInterface $visitor)
    {
        return $visitor->visitTeamRequest($this);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            'team(%s %s)',
            $this->sport->key(),
            $this->seasonName
        );
    }
}
