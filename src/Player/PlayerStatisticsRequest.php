<?php
namespace Icecave\Siphon\Player;

use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\RequestVisitorInterface;

/**
 * A request to the player statistics feed.
 */
class PlayerStatisticsRequest implements RequestInterface
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
        return $visitor->visitPlayerStatisticsRequest($this);
    }
}
