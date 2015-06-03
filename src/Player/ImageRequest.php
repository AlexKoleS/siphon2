<?php
namespace Icecave\Siphon\Player;

use Icecave\Siphon\Reader\RequestVisitorInterface;
use Icecave\Siphon\Reader\SportRequestInterface;

/**
 * A request to the player image feed.
 */
class ImageRequest implements SportRequestInterface
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
        return $visitor->visitImageRequest($this);
    }
}
