<?php

namespace Icecave\Siphon\Player;

use Countable;
use Icecave\Siphon\Reader\ResponseInterface;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Schedule\Season;
use IteratorAggregate;

/**
 * The response from a player feed.
 */
class PlayerResponse implements
    ResponseInterface,
    Countable,
    IteratorAggregate
{
    use PlayerResponseTrait;

    /**
     * Add a player to the response.
     *
     * @param Player              $player        The player to add.
     * @param PlayerSeasonDetails $seasonDetails The player's season details.
     */
    public function add(Player $player, PlayerSeasonDetails $seasonDetails)
    {
        $this->entries[$player->id()] = [$player, $seasonDetails];
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
        return $visitor->visitPlayerResponse($this);
    }
}
