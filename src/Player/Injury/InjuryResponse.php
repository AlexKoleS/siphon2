<?php
namespace Icecave\Siphon\Player\Injury;

use Countable;
use Icecave\Siphon\Player\Player;
use Icecave\Siphon\Reader\ResponseInterface;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Sport;
use IteratorAggregate;

/**
 * A response from the player injury feed.
 */
class InjuryResponse implements
    ResponseInterface,
    Countable,
    IteratorAggregate
{
    /**
     * @param Sport $sport The sport to request.
     */
    public function __construct(Sport $sport)
    {
        $this->setSport($sport);

        $this->entries = [];
    }

    /**
     * Get the requested sport.
     *
     * @return Sport The requested sport.
     */
    public function sport()
    {
        return $this->sport;
    }

    /**
     * Set the requested sport.
     *
     * @param Sport $sport The requested sport.
     */
    public function setSport(Sport $sport)
    {
        $this->sport = $sport;
    }

    /**
     * Check if the response contains players.
     *
     * @param boolean True if the response is empty; otherwise, false.
     */
    public function isEmpty()
    {
        return empty($this->entries);
    }

    /**
     * Get the number of players in the response.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->entries);
    }

    /**
     * Iterate the players.
     *
     * @return mixed<tuple<Player, Injury>>
     */
    public function getIterator()
    {
        foreach ($this->entries as $entry) {
            yield $entry;
        }
    }

    /**
     * Add a player to the response.
     *
     * @param Player $player The player to add.
     * @param Injury $injury The injury details.
     */
    public function add(Player $player, Injury $injury)
    {
        $this->entries[$player->id()] = [$player, $injury];
    }

    /**
     * Remove a player from the response.
     *
     * @param Player $player The player to remove.
     */
    public function remove(Player $player)
    {
        unset($this->entries[$player->id()]);
    }

    /**
     * Remove all players from the response.
     */
    public function clear()
    {
        $this->entries = [];
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
        return $visitor->visitInjuryResponse($this);
    }

    private $sport;
    private $entries;
}
