<?php
namespace Icecave\Siphon\Player\Statistics;

use Countable;
use Icecave\Siphon\Player\Player;
use Icecave\Siphon\Player\PlayerResponseTrait;
use Icecave\Siphon\Reader\ResponseInterface;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Statistics\StatisticsCollection;
use Icecave\Siphon\Statistics\StatisticsType;
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
     * @param Sport          $sport      The sport to request.
     * @param string         $seasonName The season name.
     * @param string|integer $teamId     The team ID.
     * @param StatisticsType $type       The type of statistics to fetch.
     */
    public function __construct(
        Sport $sport,
        $seasonName,
        $teamId,
        StatisticsType $type
    ) {
        $this->initialize($sport, $seasonName, $teamId);
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
