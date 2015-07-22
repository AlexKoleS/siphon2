<?php
namespace Icecave\Siphon\Player\Statistics;

use Countable;
use Icecave\Siphon\Player\Player;
use Icecave\Siphon\Player\PlayerResponseTrait;
use Icecave\Siphon\Reader\ResponseInterface;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Schedule\Season;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Statistics\StatisticsCollection;
use Icecave\Siphon\Statistics\StatisticsType;
use Icecave\Siphon\Team\TeamInterface;
use IteratorAggregate;

/**
 * The response from a player statistics feed.
 */
class PlayerStatisticsResponse implements
    ResponseInterface,
    Countable,
    IteratorAggregate
{
    use PlayerResponseTrait {
        __construct as private initialize;
    }

    /**
     * @param Sport          $sport  The sport to request.
     * @param Season         $season The season.
     * @param TeamInterface  $team   The team.
     * @param StatisticsType $type   The type of statistics to fetch.
     */
    public function __construct(
        Sport $sport,
        Season $season,
        TeamInterface $team,
        StatisticsType $type
    ) {
        $this->initialize($sport, $season, $team);
        $this->setType($type);
    }

    /**
     * Get the requested statistics type.
     *
     * @return StatisticsType The requested statistics type.
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * Set the requested statistics type.
     *
     * @param StatisticsType $type The requested statistics type.
     */
    public function setType(StatisticsType $type)
    {
        $this->type = $type;
    }

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

    private $type;
}
