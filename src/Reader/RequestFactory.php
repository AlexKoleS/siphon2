<?php
namespace Icecave\Siphon\Reader;

use Icecave\Chrono\DateTime;
use Icecave\Siphon\Atom\AtomRequest;
use Icecave\Siphon\Player\Image\ImageRequest;
use Icecave\Siphon\Player\Injury\InjuryRequest;
use Icecave\Siphon\Player\PlayerRequest;
use Icecave\Siphon\Player\Statistics\PlayerStatisticsRequest;
use Icecave\Siphon\Schedule\ScheduleRequest;
use Icecave\Siphon\Schedule\ScheduleType;
use Icecave\Siphon\Score\BoxScore\BoxScoreRequest;
use Icecave\Siphon\Score\LiveScore\LiveScoreRequest;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Statistics\StatisticsType;
use Icecave\Siphon\Team\Statistics\TeamStatisticsRequest;
use Icecave\Siphon\Team\TeamRequest;
use InvalidArgumentException;
use stdClass;

/**
 * Construct RequestInterface objects from the URLs returned by the Atom feed.
 */
class RequestFactory implements RequestFactoryInterface
{
    /**
     * Create a request from a URL.
     *
     * @param string $url The URL.
     *
     * @return RequestInterface         A request object representing a request to the given URL.
     * @throws InvalidArgumentException If the URL can not be mapped to an appropriate request object.
     */
    public function create($url)
    {
        $components = (object) parse_url($url);

        if (isset($components->query)) {
            $query = [];

            parse_str(
                $components->query,
                $query
            );

            $components->query = (object) $query;
        } else {
            $components->query = (object) [];
        }

        if ($request = $this->createAtomRequest($components)) {
            return $request;
        } elseif ($request = $this->createScheduleRequest($components)) {
            return $request;
        } elseif ($request = $this->createTeamRequest($components)) {
            return $request;
        } elseif ($request = $this->createPlayerRequest($components)) {
            return $request;
        } elseif ($request = $this->createTeamStatisticsRequest($components)) {
            return $request;
        } elseif ($request = $this->createPlayerStatisticsRequest($components)) {
            return $request;
        } elseif ($request = $this->createImageRequest($components)) {
            return $request;
        } elseif ($request = $this->createInjuryRequest($components)) {
            return $request;
        } elseif ($request = $this->createLiveScoreRequest($components)) {
            return $request;
        } elseif ($request = $this->createBoxScoreRequest($components)) {
            return $request;
        }

        throw new InvalidArgumentException('Unsupported URL.');
    }

    /**
     * Attempt to create an AtomRequest.
     *
     * @param stdClass $components The URL components.
     *
     * @return AtomRequest|null
     */
    private function createAtomRequest(stdClass $components)
    {
        if ('/Atom' !== $components->path) {
            return null;
        }

        $request = new AtomRequest(
            DateTime::fromIsoString($components->query->newerThan)
        );

        if (isset($components->query->feed)) {
            $request->setFeed($components->query->feed);
        }

        if (isset($components->query->limit)) {
            $request->setLimit(intval($components->query->limit));
        }

        if (isset($components->query->order)) {
            $order = $components->query->order;

            if ('asc' === $order) {
                $order = SORT_ASC;
            } elseif ('desc' === $order) {
                $order = SORT_DESC;
            }

            $request->setOrder($order);
        }

        return $request;
    }

    /**
     * Attempt to create a ScheduleRequest.
     *
     * @param stdClass $components The URL components.
     *
     * @return ScheduleRequest|null
     */
    private function createScheduleRequest(stdClass $components)
    {
        $matches = [];

        // full schedule ...
        if (preg_match(
            '{^/sport/v2/([a-z]+)/([A-Z]+)/schedule/schedule_\2\.xml$}',
            $components->path,
            $matches
        )) {
            $sport = Sport::findByComponents($matches[1], $matches[2]);
            $type  = ScheduleType::FULL();

        // limited ...
        } elseif (preg_match(
            '{^/sport/v2/([a-z]+)/([A-Z]+)/schedule/schedule_\2_(\d+)_days.xml$}',
            $components->path,
            $matches
        )) {
            $sport = Sport::findByComponents($matches[1], $matches[2]);
            $type  = ScheduleType::memberByValue(intval($matches[3]));

        // deleted ...
        } elseif (preg_match(
            '{^/sport/v2/([a-z]+)/([A-Z]+)/games-deleted/games_deleted_\2\.xml$}',
            $components->path,
            $matches
        )) {
            $sport = Sport::findByComponents($matches[1], $matches[2]);
            $type  = ScheduleType::DELETED();
        } else {
            return null;
        }

        return new ScheduleRequest($sport, $type);
    }

    /**
     * Attempt to create a TeamRequest.
     *
     * @param stdClass $components The URL components.
     *
     * @return TeamRequest|null
     */
    private function createTeamRequest(stdClass $components)
    {
        $matches = [];

        if (preg_match(
            '{^/sport/v2/([a-z]+)/([A-Z]+)/teams/([^/]+)/teams_\2\.xml$}',
            $components->path,
            $matches
        )) {
            return new TeamRequest(
                Sport::findByComponents($matches[1], $matches[2]),
                $matches[3]
            );
        }

        return null;
    }

    /**
     * Attempt to create a PlayerRequest.
     *
     * @param stdClass $components The URL components.
     *
     * @return PlayerRequest|null
     */
    private function createPlayerRequest(stdClass $components)
    {
        $matches = [];

        if (preg_match(
            '{^/sport/v2/([a-z]+)/([A-Z]+)/players/([^/]+)/players_(\d+)_\2\.xml$}',
            $components->path,
            $matches
        )) {
            return new PlayerRequest(
                Sport::findByComponents($matches[1], $matches[2]),
                $matches[3],
                $matches[4]
            );
        }

        return null;
    }

    /**
     * Attempt to create a TeamStatisticsRequest.
     *
     * @param stdClass $components The URL components.
     *
     * @return TeamStatisticsRequest|null
     */
    private function createTeamStatisticsRequest(stdClass $components)
    {
        $matches = [];

        if (preg_match(
            '{^/sport/v2/([a-z]+)/([A-Z]+)/team-stats/([^/]+)/team_stats_\2\.xml$}',
            $components->path,
            $matches
        )) {
            return new TeamStatisticsRequest(
                Sport::findByComponents($matches[1], $matches[2]),
                $matches[3],
                StatisticsType::COMBINED()
            );
        } elseif (preg_match(
            '{^/sport/v2/([a-z]+)/([A-Z]+)/team-split-stats/([^/]+)/team_split_stats_\2\.xml$}',
            $components->path,
            $matches
        )) {
            return new TeamStatisticsRequest(
                Sport::findByComponents($matches[1], $matches[2]),
                $matches[3],
                StatisticsType::SPLIT()
            );
        }

        return null;
    }

    /**
     * Attempt to create a PlayerStatisticsRequest.
     *
     * @param stdClass $components The URL components.
     *
     * @return PlayerStatisticsRequest|null
     */
    private function createPlayerStatisticsRequest(stdClass $components)
    {
        $matches = [];

        if (preg_match(
            '{^/sport/v2/([a-z]+)/([A-Z]+)/player-stats/([^/]+)/player_stats_(\d+)_\2\.xml$}',
            $components->path,
            $matches
        )) {
            return new PlayerStatisticsRequest(
                Sport::findByComponents($matches[1], $matches[2]),
                $matches[3],
                $matches[4],
                StatisticsType::COMBINED()
            );
        } elseif (preg_match(
            '{^/sport/v2/([a-z]+)/([A-Z]+)/player-split-stats/([^/]+)/player_split_stats_(\d+)_\2\.xml$}',
            $components->path,
            $matches
        )) {
            return new PlayerStatisticsRequest(
                Sport::findByComponents($matches[1], $matches[2]),
                $matches[3],
                $matches[4],
                StatisticsType::SPLIT()
            );
        }

        return null;
    }

    /**
     * Attempt to create a ImageRequest.
     *
     * @param stdClass $components The URL components.
     *
     * @return ImageRequest|null
     */
    private function createImageRequest(stdClass $components)
    {
        $matches = [];

        if (preg_match(
            '{^/sport/v2/([a-z]+)/([A-Z]+)/player-images/([^/]+)/player-images_(\d+)_\2\.xml$}',
            $components->path,
            $matches
        )) {
            return new ImageRequest(
                Sport::findByComponents($matches[1], $matches[2]),
                $matches[3],
                $matches[4]
            );
        }

        return null;
    }

    /**
     * Attempt to create a InjuryRequest.
     *
     * @param stdClass $components The URL components.
     *
     * @return InjuryRequest|null
     */
    private function createInjuryRequest(stdClass $components)
    {
        $matches = [];

        if (preg_match(
            '{^/sport/v2/([a-z]+)/([A-Z]+)/injuries/injuries_\2\.xml$}',
            $components->path,
            $matches
        )) {
            return new InjuryRequest(
                Sport::findByComponents($matches[1], $matches[2])
            );
        }

        return null;
    }

    /**
     * Attempt to create a LiveScoreRequest.
     *
     * @param stdClass $components The URL components.
     *
     * @return LiveScoreRequest|null
     */
    private function createLiveScoreRequest(stdClass $components)
    {
        $matches = [];

        if (preg_match(
            '{^/sport/v2/([a-z]+)/([A-Z]+)/livescores/livescores_(\d+)\.xml$}',
            $components->path,
            $matches
        )) {
            return new LiveScoreRequest(
                Sport::findByComponents($matches[1], $matches[2]),
                $matches[3]
            );
        }

        return null;
    }

    /**
     * Attempt to create a BoxScoreRequest.
     *
     * @param stdClass $components The URL components.
     *
     * @return BoxScoreRequest|null
     */
    private function createBoxScoreRequest(stdClass $components)
    {
        $matches = [];

        if (preg_match(
            '{^/sport/v2/([a-z]+)/([A-Z]+)/boxscores/([^/]+)/boxscore_\2_(\d+)\.xml$}',
            $components->path,
            $matches
        )) {
            return new BoxScoreRequest(
                Sport::findByComponents($matches[1], $matches[2]),
                $matches[3],
                $matches[4]
            );
        }

        return null;
    }
}
