<?php
namespace Icecave\Siphon\Reader;

use Icecave\Siphon\Atom\AtomRequest;
use Icecave\Siphon\Player\Image\ImageRequest;
use Icecave\Siphon\Player\Injury\InjuryRequest;
use Icecave\Siphon\Player\PlayerRequest;
use Icecave\Siphon\Player\Statistics\PlayerStatisticsRequest;
use Icecave\Siphon\Schedule\ScheduleRequest;
use Icecave\Siphon\Score\LiveScore\LiveScoreRequest;
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
     * @param TeamRequest $request
     *
     * @return mixed
     */
    public function visitTeamRequest(TeamRequest $request);

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
}
