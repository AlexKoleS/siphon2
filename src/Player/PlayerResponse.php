<?php
namespace Icecave\Siphon\Player;

use Countable;
use Icecave\Siphon\Reader\ResponseInterface;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Schedule\SeasonInterface;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Team\TeamInterface;
use IteratorAggregate;

/**
 * The response from a player feed.
 */
class PlayerResponse implements
    ResponseInterface,
    Countable,
    IteratorAggregate
{
    public function __construct(
        Sport $sport,
        SeasonInterface $season,
        TeamInterface $team
    ) {
        $this->setSport($sport);
        $this->setSeason($season);
        $this->setTeam($team);

        $this->players = [];
        $this->seasonDetails = [];
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
     * Get the requested season.
     *
     * @return SeasonInterface The requested season.
     */
    public function season()
    {
        return $this->season;
    }

    /**
     * Set the requested season.
     *
     * @param SeasonInterface $season The requested season.
     */
    public function setSeason(SeasonInterface $season)
    {
        $this->season = $season;
    }

    /**
     * Get the requested team.
     *
     * @return TeamInterface The requested team.
     */
    public function team()
    {
        return $this->team;
    }

    /**
     * Set the requested team.
     *
     * @param TeamInterface $team The requested team.
     */
    public function setTeam(TeamInterface $team)
    {
        $this->team = $team;
    }

    /**
     * Check if the response contains players.
     *
     * @param boolean True if the response is empty; otherwise, false.
     */
    public function isEmpty()
    {
        return empty($this->players);
    }

    /**
     * Get the number of players in the response.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->players);
    }

    /**
     * Iterate the players.
     *
     * @return mixed<Player>
     */
    public function getIterator()
    {
        foreach ($this->players as $player) {
            yield $player;
        }
    }

    /**
     * Add a player to the response.
     *
     * @param Player $player The player to add.
     * @param PlayerSeasonDetails|null $seasonDetails The player's season details.
     */
    public function add(Player $player, PlayerSeasonDetails $seasonDetails = null)
    {
        $this->players[$player->id()] = $player;

        if ($seasonDetails) {
            $this->seasonDetails[$player->id()] = $seasonDetails;
        }
    }

    /**
     * Remove a player from the response.
     *
     * @param Player $player The player to remove.
     */
    public function remove(Player $player)
    {
        unset($this->players[$player->id()]);
        unset($this->seasonDetails[$player->id()]);
    }

    /**
     * Remove all players from the response.
     */
    public function clear()
    {
        $this->players = [];
        $this->seasonDetails = [];
    }

    /**
     * Get the season details for the given player, if present.
     *
     * @param Player $player The player.
     *
     * @return PlayerSeasonDetails|null
     */
    public function findSeasonDetails(Player $player)
    {
        if (isset($this->seasonDetails[$player->id()])) {
            return $this->seasonDetails[$player->id()];
        }

        return null;
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

    private $sport;
    private $type;
    private $team;
    private $players;
    private $seasonDetails;
}
