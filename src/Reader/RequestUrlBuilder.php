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
use Icecave\Siphon\Schedule\ScheduleType;
use Icecave\Siphon\Score\BoxScore\BoxScoreRequest;
use Icecave\Siphon\Score\LiveScore\LiveScoreRequest;
use Icecave\Siphon\Statistics\StatisticsType;
use Icecave\Siphon\Team\Statistics\TeamStatisticsRequest;
use Icecave\Siphon\Team\TeamRequest;

/**
 * Builds request URLs.
 */
class RequestUrlBuilder implements RequestUrlBuilderInterface,
    RequestVisitorInterface
{
    /**
     * @param UrlBuilderInterface $urlBuilder The URL builder used to resolve feed URLs.
     */
    public function __construct(UrlBuilderInterface $urlBuilder)
    {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Build a feed URL from a request object.
     *
     * @param RequestInterface $request The request.
     *
     * @return string The fully-qualified feed URL.
     */
    public function build(RequestInterface $request)
    {
        return $request->accept($this);
    }

    /**
     * Visit the given request.
     *
     * @param AtomRequest $request
     *
     * @return mixed
     */
    public function visitAtomRequest(AtomRequest $request)
    {
        $parameters = [
            'newerThan' => $request->updatedTime(),
            'maxCount'  => $request->limit(),
        ];

        if (SORT_ASC === $request->order()) {
            $parameters['order'] = 'asc';
        } else {
            $parameters['order'] = 'desc';
        }

        if (null !== $request->feed()) {
            $parameters['feed'] = $request->feed();
        }

        return $this->urlBuilder->build('/Atom', $parameters);
    }

    /**
     * Visit the given request.
     *
     * @param ScheduleRequest $request
     *
     * @return mixed
     */
    public function visitScheduleRequest(ScheduleRequest $request)
    {
        $sport = $request->sport();
        $league = $sport->league();
        $sport = $sport->sport(); // not a typo

        if (ScheduleType::FULL() === $request->type()) {
            $resource = sprintf(
                '/sport/v2/%s/%s/schedule/schedule_%s.xml',
                $sport,
                $league,
                $league
            );
        } elseif (ScheduleType::DELETED() === $request->type()) {
            $resource = sprintf(
                '/sport/v2/%s/%s/games-deleted/games_deleted_%s.xml',
                $sport,
                $league,
                $league
            );
        } else {
            $resource = sprintf(
                '/sport/v2/%s/%s/schedule/schedule_%s_%d_days.xml',
                $sport,
                $league,
                $league,
                $request->type()->value()
            );
        }

        return $this->urlBuilder->build($resource);
    }

    /**
     * Visit the given request.
     *
     * @param ResultRequest $request
     *
     * @return mixed
     */
    public function visitResultRequest(ResultRequest $request)
    {
        $sport = $request->sport();
        $league = $sport->league();
        $sport = $sport->sport(); // not a typo

        return $this->urlBuilder->build(
            sprintf(
                '/sport/v2/%s/%s/results/%s/results_%s.xml',
                $sport,
                $league,
                $request->seasonName(),
                $league
            )
        );
    }

    /**
     * Visit the given request.
     *
     * @param TeamRequest $request
     *
     * @return mixed
     */
    public function visitTeamRequest(TeamRequest $request)
    {
        $sport = $request->sport();
        $league = $sport->league();
        $sport = $sport->sport(); // not a typo

        return $this->urlBuilder->build(
            sprintf(
                '/sport/v2/%s/%s/teams/%s/teams_%s.xml',
                $sport,
                $league,
                $request->seasonName(),
                $league
            )
        );
    }

    /**
     * Visit the given request.
     *
     * @param TeamStatisticsRequest $request
     *
     * @return mixed
     */
    public function visitTeamStatisticsRequest(TeamStatisticsRequest $request)
    {
        $sport = $request->sport();
        $league = $sport->league();
        $sport = $sport->sport(); // not a typo

        if (StatisticsType::COMBINED() === $request->type()) {
            $resource = sprintf(
                '/sport/v2/%s/%s/team-stats/%s/team_stats_%s.xml',
                $sport,
                $league,
                $request->seasonName(),
                $league
            );
        } else { // split stats
            $resource = sprintf(
                '/sport/v2/%s/%s/team-split-stats/%s/team_split_stats_%s.xml',
                $sport,
                $league,
                $request->seasonName(),
                $league
            );
        }

        return $this->urlBuilder->build($resource);
    }

    /**
     * Visit the given request.
     *
     * @param PlayerRequest $request
     *
     * @return mixed
     */
    public function visitPlayerRequest(PlayerRequest $request)
    {
        $sport = $request->sport();
        $league = $sport->league();
        $sport = $sport->sport(); // not a typo

        return $this->urlBuilder->build(
            sprintf(
                '/sport/v2/%s/%s/players/%s/players_%d_%s.xml',
                $sport,
                $league,
                $request->seasonName(),
                $request->teamId(),
                $league
            )
        );
    }

    /**
     * Visit the given request.
     *
     * @param PlayerStatisticsRequest $request
     *
     * @return mixed
     */
    public function visitPlayerStatisticsRequest(PlayerStatisticsRequest $request)
    {
        $sport = $request->sport();
        $league = $sport->league();
        $sport = $sport->sport(); // not a typo

        if (StatisticsType::COMBINED() === $request->type()) {
            $resource = sprintf(
                '/sport/v2/%s/%s/player-stats/%s/player_stats_%d_%s.xml',
                $sport,
                $league,
                $request->seasonName(),
                $request->teamId(),
                $league
            );
        } else { // split stats
            $resource = sprintf(
                '/sport/v2/%s/%s/player-split-stats/%s/' .
                    'player_split_stats_%d_%s.xml',
                $sport,
                $league,
                $request->seasonName(),
                $request->teamId(),
                $league
            );
        }

        return $this->urlBuilder->build($resource);
    }

    /**
     * Visit the given request.
     *
     * @param ImageRequest $request
     *
     * @return mixed
     */
    public function visitImageRequest(ImageRequest $request)
    {
        $sport = $request->sport();
        $league = $sport->league();
        $sport = $sport->sport(); // not a typo

        return $this->urlBuilder->build(
            sprintf(
                '/sport/v2/%s/%s/player-images/%s/player-images_%d_%s.xml',
                $sport,
                $league,
                $request->seasonName(),
                $request->teamId(),
                $league
            )
        );
    }

    /**
     * Visit the given request.
     *
     * @param InjuryRequest $request
     *
     * @return mixed
     */
    public function visitInjuryRequest(InjuryRequest $request)
    {
        $sport = $request->sport();
        $league = $sport->league();
        $sport = $sport->sport(); // not a typo

        return $this->urlBuilder->build(
            sprintf(
                '/sport/v2/%s/%s/injuries/injuries_%s.xml',
                $sport,
                $league,
                $league
            )
        );
    }

    /**
     * Visit the given request.
     *
     * @param LiveScoreRequest $request
     *
     * @return mixed
     */
    public function visitLiveScoreRequest(LiveScoreRequest $request)
    {
        $sport = $request->sport();
        $league = $sport->league();
        $sport = $sport->sport(); // not a typo

        return $this->urlBuilder->build(
            sprintf(
                '/sport/v2/%s/%s/livescores/livescores_%d.xml',
                $sport,
                $league,
                $request->competitionId()
            )
        );
    }

    /**
     * Visit the given request.
     *
     * @param BoxScoreRequest $request
     *
     * @return mixed
     */
    public function visitBoxScoreRequest(BoxScoreRequest $request)
    {
        $sport = $request->sport();
        $league = $sport->league();
        $sport = $sport->sport(); // not a typo

        return $this->urlBuilder->build(
            sprintf(
                '/sport/v2/%s/%s/boxscores/%s/boxscore_%s_%d.xml',
                $sport,
                $league,
                $request->seasonName(),
                $league,
                $request->competitionId()
            )
        );
    }

    /**
     * Visit the given request.
     *
     * @param HockeyProbableGoaliesRequest $request
     *
     * @return mixed
     */
    public function visitHockeyProbableGoaliesRequest(HockeyProbableGoaliesRequest $request)
    {
        $sport = $request->sport();
        $league = $sport->league();
        $sport = $sport->sport(); // not a typo

        return $this->urlBuilder->build(
            sprintf(
                '/sport/v2/%s/%s/probable-goalies/probable_goalies_%s.xml',
                $sport,
                $league,
                $league
            )
        );
    }

    private $urlBuilder;
}
