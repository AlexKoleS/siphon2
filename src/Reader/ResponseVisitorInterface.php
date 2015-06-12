<?php
namespace Icecave\Siphon\Reader;

use Icecave\Siphon\Atom\AtomResponse;
use Icecave\Siphon\Player\Image\ImageResponse;
use Icecave\Siphon\Player\Injury\InjuryResponse;
use Icecave\Siphon\Player\PlayerResponse;
use Icecave\Siphon\Player\Statistics\PlayerStatisticsResponse;
use Icecave\Siphon\Schedule\ScheduleResponse;
use Icecave\Siphon\Score\BoxScore\BoxScoreResponse;
use Icecave\Siphon\Score\LiveScore\LiveScoreResponse;
use Icecave\Siphon\Team\TeamResponse;

/**
 * Response visitor.
 */
interface ResponseVisitorInterface
{
    /**
     * Visit the given response.
     *
     * @param AtomResponse $response
     *
     * @return mixed
     */
    public function visitAtomResponse(AtomResponse $response);

    /**
     * Visit the given response.
     *
     * @param ScheduleResponse $response
     *
     * @return mixed
     */
    public function visitScheduleResponse(ScheduleResponse $response);

    /**
     * Visit the given response.
     *
     * @param TeamResponse $response
     *
     * @return mixed
     */
    public function visitTeamResponse(TeamResponse $response);

    /**
     * Visit the given response.
     *
     * @param PlayerResponse $response
     *
     * @return mixed
     */
    public function visitPlayerResponse(PlayerResponse $response);

    /**
     * Visit the given response.
     *
     * @param PlayerStatisticsResponse $response
     *
     * @return mixed
     */
    public function visitPlayerStatisticsResponse(PlayerStatisticsResponse $response);

    /**
     * Visit the given response.
     *
     * @param ImageResponse $response
     *
     * @return mixed
     */
    public function visitImageResponse(ImageResponse $response);

    /**
     * Visit the given response.
     *
     * @param InjuryResponse $response
     *
     * @return mixed
     */
    public function visitInjuryResponse(InjuryResponse $response);

    /**
     * Visit the given response.
     *
     * @param LiveScoreResponse $response
     *
     * @return mixed
     */
    public function visitLiveScoreResponse(LiveScoreResponse $response);

    /**
     * Visit the given response.
     *
     * @param BoxScoreResponse $response
     *
     * @return mixed
     */
    public function visitBoxScoreResponse(BoxScoreResponse $response);
}
