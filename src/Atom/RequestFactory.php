<?php
namespace Icecave\Siphon\Atom;

use Icecave\Chrono\DateTime;
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

        if (!isset($components->path)) {
            $components->path = '/';
        }

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
        }

        throw new InvalidArgumentException('Unsupported URL.');
    }

    /**
     * Attempt to create an AtomRequest.
     *
     * @param array $components The URL components.
     *
     * @return AtomRequest|null
     */
    public function createAtomRequest(stdClass $components)
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
}
