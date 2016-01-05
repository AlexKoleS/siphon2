<?php

namespace Icecave\Siphon\Score\BoxScore;

use Countable;
use Icecave\Siphon\Player\Player;
use Icecave\Siphon\Reader\ResponseInterface;
use Icecave\Siphon\Reader\ResponseTrait;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Schedule\CompetitionInterface;
use Icecave\Siphon\Score\Score;
use Icecave\Siphon\Statistics\StatisticsCollection;
use Icecave\Siphon\Team\TeamInterface;
use InvalidArgumentException;
use IteratorAggregate;

/**
 * The response from a box score feed.
 */
class BoxScoreResponse implements
    ResponseInterface,
    Countable,
    IteratorAggregate
{
    use ResponseTrait;

    public function __construct(
        CompetitionInterface $competition,
        StatisticsCollection $homeTeamStatistics,
        StatisticsCollection $awayTeamStatistics
    ) {
        $this->setCompetition($competition);
        $this->setHomeTeamStatistics($homeTeamStatistics);
        $this->setAwayTeamStatistics($awayTeamStatistics);
        $this->setIsFinalized(false);

        $this->playerStatistics = [];
    }

    /**
     * Get the requested competition.
     *
     * @return CompetitionInterface
     */
    public function competition()
    {
        return $this->competition;
    }

    /**
     * Set the requested competition.
     *
     * @param CompetitionInterface $competition
     */
    public function setCompetition(CompetitionInterface $competition)
    {
        $this->competition = $competition;
    }

    /**
     * Get the home team statistics.
     *
     * @return StatisticsCollection
     */
    public function homeTeamStatistics()
    {
        return $this->homeTeamStatistics;
    }

    /**
     * Set the home team statistics.
     *
     * @param StatisticsCollection $statistics
     */
    public function setHomeTeamStatistics(StatisticsCollection $statistics)
    {
        $this->homeTeamStatistics = $statistics;
    }

    /**
     * Get the away team statistics.
     *
     * @return StatisticsCollection
     */
    public function awayTeamStatistics()
    {
        return $this->awayTeamStatistics;
    }

    /**
     * Set the away team statistics.
     *
     * @param StatisticsCollection $statistics
     */
    public function setAwayTeamStatistics(StatisticsCollection $statistics)
    {
        $this->awayTeamStatistics = $statistics;
    }

    /**
     * Check if the box score QA status is finalized.
     *
     * @return boolean
     */
    public function isFinalized()
    {
        return $this->isFinalized;
    }

    /**
     * Check if the box score QA status is finalized.
     *
     * @return boolean
     */
    public function setIsFinalized($isFinalized)
    {
        return $this->isFinalized = $isFinalized;
    }

    /**
     * Add a player to the response.
     *
     * @param TeamInterface        $team       The player's team.
     * @param Player               $player     The player to add.
     * @param StatisticsCollection $statistics The player's statistics.
     */
    public function add(
        TeamInterface $team,
        Player $player,
        StatisticsCollection $statistics
    ) {
        if ($team === $this->competition->homeTeam()) {
            // same instance
        } elseif ($team === $this->competition->awayTeam()) {
            // same instance
        } else {
            throw new InvalidArgumentException(
                'The team object must be one of the ' . TeamInterface::class . ' instances from the competition object.'
            );
        }

        $this->playerStatistics[$player->id()] = [$team, $player, $statistics];
    }

    /**
     * Remove a player from the response.
     *
     * @param Player $player The player to remove.
     */
    public function remove(Player $player)
    {
        unset($this->playerStatistics[$player->id()]);
    }

    /**
     * Remove all players from the response.
     */
    public function clear()
    {
        $this->playerStatistics = [];
    }

    /**
     * Check if the response contains players.
     *
     * @param boolean True if the response is empty; otherwise, false.
     */
    public function isEmpty()
    {
        return empty($this->playerStatistics);
    }

    /**
     * Get the number of players in the response.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->playerStatistics);
    }

    /**
     * Iterate the players.
     *
     * @param TeamInterface|hull $team Limit results to players from the given team, or null for all players.
     *
     * @return mixed<tuple<TeamInterface, Player, StatisticsCollection>>
     */
    public function getIterator(TeamInterface $team = null)
    {
        foreach ($this->playerStatistics as $entry) {
            if ($team === null || $entry[0] === $team) {
                yield $entry;
            }
        }
    }

    /**
     * Iterate the home team players.
     *
     * @return mixed<tuple<TeamInterface, Player, StatisticsCollection>>
     */
    public function homeTeamPlayers()
    {
        return $this->getIterator(
            $this->competition->homeTeam()
        );
    }

    /**
     * Iterate the away team players.
     *
     * @return mixed<tuple<TeamInterface, Player, StatisticsCollection>>
     */
    public function awayTeamPlayers()
    {
        return $this->getIterator(
            $this->competition->awayTeam()
        );
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
        return $visitor->visitBoxScoreResponse($this);
    }

    private $competition;
    private $homeTeamStatistics;
    private $awayTeamStatistics;
    private $playerStatistics;
    private $isFinalized;
}
