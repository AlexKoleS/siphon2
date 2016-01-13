<?php

namespace Icecave\Siphon\Reader;

use Icecave\Siphon\Atom\AtomRequest;
use Icecave\Siphon\Hockey\ProbableGoalies\HockeyProbableGoaliesRequest;
use Icecave\Siphon\Player\Image\ImageRequest;
use Icecave\Siphon\Player\Injury\InjuryRequest;
use Icecave\Siphon\Player\PlayerRequest;
use Icecave\Siphon\Player\Statistics\PlayerStatisticsRequest;
use Icecave\Siphon\Result\ResultRequest;
use Icecave\Siphon\Schedule\ScheduleRequest;
use Icecave\Siphon\Score\BoxScore\BoxScoreRequest;
use Icecave\Siphon\Score\LiveScore\LiveScoreRequest;
use Icecave\Siphon\Team\Statistics\TeamStatisticsRequest;
use Icecave\Siphon\Team\TeamRequest;

/**
 * Request visitor.
 */
interface RequestVisitorInterface
{
    /**
     * Visit the given request.
     *
     * @param AtomRequest $request
     *
     * @return mixed
     */
    public function visitAtomRequest(AtomRequest $request);

    /**
     * Visit the given request.
     *
     * @param ScheduleRequest $request
     *
     * @return mixed
     */
    public function visitScheduleRequest(ScheduleRequest $request);

    /**
     * Visit the given request.
     *
     * @param ResultRequest $request
     *
     * @return mixed
     */
    public function visitResultRequest(ResultRequest $request);

    /**
     * Visit the given request.
     *
     * @param TeamRequest $request
     *
     * @return mixed
     */
    public function visitTeamRequest(TeamRequest $request);

    /**
     * Visit the given request.
     *
     * @param TeamStatisticsRequest $request
     *
     * @return mixed
     */
    public function visitTeamStatisticsRequest(TeamStatisticsRequest $request);

    /**
     * Visit the given request.
     *
     * @param PlayerRequest $request
     *
     * @return mixed
     */
    public function visitPlayerRequest(PlayerRequest $request);

    /**
     * Visit the given request.
     *
     * @param PlayerStatisticsRequest $request
     *
     * @return mixed
     */
    public function visitPlayerStatisticsRequest(PlayerStatisticsRequest $request);

    /**
     * Visit the given request.
     *
     * @param ImageRequest $request
     *
     * @return mixed
     */
    public function visitImageRequest(ImageRequest $request);

    /**
     * Visit the given request.
     *
     * @param InjuryRequest $request
     *
     * @return mixed
     */
    public function visitInjuryRequest(InjuryRequest $request);

    /**
     * Visit the given request.
     *
     * @param LiveScoreRequest $request
     *
     * @return mixed
     */
    public function visitLiveScoreRequest(LiveScoreRequest $request);

    /**
     * Visit the given request.
     *
     * @param BoxScoreRequest $request
     *
     * @return mixed
     */
    public function visitBoxScoreRequest(BoxScoreRequest $request);

    /**
     * Visit the given request.
     *
     * @param HockeyProbableGoaliesRequest $request
     *
     * @return mixed
     */
    public function visitHockeyProbableGoaliesRequest(HockeyProbableGoaliesRequest $request);
}
