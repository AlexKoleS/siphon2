<?php

namespace Icecave\Siphon\Player\Image;

use Countable;
use Icecave\Siphon\Player\Player;
use Icecave\Siphon\Player\PlayerResponseTrait;
use Icecave\Siphon\Reader\ResponseTrait;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Reader\SportResponseInterface;
use IteratorAggregate;

/**
 * The response from a player image feed.
 */
class ImageResponse implements
    SportResponseInterface,
    Countable,
    IteratorAggregate
{
    use ResponseTrait;
    use PlayerResponseTrait;

    /**
     * Add a player to the response.
     *
     * @param Player $player          The player to add.
     * @param string $smallImage|null The URL of the small (thumbnail) head-shot image.
     * @param string $largeImage|null The URL of the large head-shot image.
     */
    public function add(Player $player, $smallImage, $largeImage)
    {
        $this->entries[$player->id()] = [$player, $smallImage, $largeImage];
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
        return $visitor->visitImageResponse($this);
    }
}
