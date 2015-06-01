<?php
namespace Icecave\Siphon\Player;

use Countable;
use Icecave\Siphon\Reader\ResponseInterface;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Statistics\StatisticsCollection;
use IteratorAggregate;

/**
 * The response from a player statistics feed.
 */
class PlayerStatisticsResponse implements
    ResponseInterface,
    Countable,
    IteratorAggregate
{
    use PlayerResponseTrait;

    /**
     * Add a player to the response.
     *
     * @param Player               $player     The player to add.
     * @param StatisticsCollection $statistics The player's statistics.
     */
    public function add(Player $player, StatisticsCollection $statistics)
    {
        $this->entries[$player->id()] = [$player, $statistics];
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
        return $visitor->visitPlayerStatisticsResponse($this);
    }
}
