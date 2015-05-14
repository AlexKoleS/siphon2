<?php
namespace Icecave\Siphon\Atom;

use Icecave\Chrono\DateTime;
use Icecave\Siphon\Schedule\ScheduleRequest;
use Icecave\Siphon\Schedule\ScheduleType;
use Icecave\Siphon\Sport;
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
    public function createScheduleRequest(stdClass $components)
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
}
