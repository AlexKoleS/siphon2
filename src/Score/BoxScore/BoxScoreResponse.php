<?php
namespace Icecave\Siphon\Score\BoxScore;

use Countable;
use Icecave\Siphon\Player\Player;
use Icecave\Siphon\Reader\ResponseInterface;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Schedule\CompetitionInterface;
use Icecave\Siphon\Score\Score;
use Icecave\Siphon\Statistics\StatisticsCollection;
use IteratorAggregate;

/**
 * The response from a box score feed.
 */
class BoxScoreResponse implements
    ResponseInterface,
    Countable,
    IteratorAggregate
{
    public function __construct(
        CompetitionInterface $competition,
        StatisticsCollection $homeTeamStatistics,
        StatisticsCollection $awayTeamStatistics
    ) {
        $this->setCompetition($competition);
        $this->setHomeTeamStatistics($homeTeamStatistics);
        $this->setAwayTeamStatistics($awayTeamStatistics);

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
     * Add a player to the response.
     *
     * @param Player               $player     The player to add.
     * @param StatisticsCollection $statistics The player's statistics.
     */
    public function add(Player $player, StatisticsCollection $statistics)
    {
        $this->playerStatistics[$player->id()] = [$player, $statistics];
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
     * @return mixed<tuple<Player, mixed>>
     */
    public function getIterator()
    {
        foreach ($this->playerStatistics as $entry) {
            yield $entry;
        }
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
}
