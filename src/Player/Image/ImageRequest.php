<?php

namespace Icecave\Siphon\Player\Image;

use Icecave\Siphon\Player\PlayerRequestTrait;
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

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            'image(%s %s team:%d)',
            $this->sport->key(),
            $this->seasonName,
            $this->teamId
        );
    }
}
