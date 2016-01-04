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
     * Fetch the request's "rate-limit group".
     *
     * If the request is rate-limited, any other requests that are in the same
     * rate-limit group are also rate limited.
     *
     * @return string The rate-limit group.
     */
    public function rateLimitGroup()
    {
        return sprintf(
            'image(%s)',
            $this->sport->key()
        );
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
