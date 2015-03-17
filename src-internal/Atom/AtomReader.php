<?php
namespace Icecave\Siphon\Atom;

use Icecave\Chrono\DateTime;
use Icecave\Siphon\XmlReaderInterface;
use InvalidArgumentException;

/**
 * Read data from the Atom feed.
 *
 * Atom feeds are used to poll for changes to the sports data feeds.
 */
class AtomReader implements AtomReaderInterface
{
    public function __construct(XmlReaderInterface $xmlReader)
    {
        $this->xmlReader = $xmlReader;
    }

    /**
     * Fetch a list of feeds that have been updated since the given threshold
     * time.
     *
     * @param DateTime    $threshold Limit results to feeds updated after this time point.
     * @param string|null $feed      Limit results to feeds of the given type, or null for any type.
     * @param integer     $limit     The maximum number of results to return.
     * @param integer     $order     The sort order (one of SORT_ASC or SORT_DESC).
     *
     * @return AtomResultInterface
     */
    public function read(
        DateTime $threshold,
        $feed = null,
        $limit = 5000,
        $order = SORT_ASC
    ) {
        $xml = $this->xmlReader->read(
            '/Atom',
            $this->buildParameters(
                $threshold,
                $feed,
                $limit,
                $order
            )
        );

        $result = new AtomResult(
            DateTime::fromIsoString($xml->updated)
        );

        foreach ($xml->entry as $entry) {
            $result->add(
                new AtomEntry(
                    strval($entry->link['href']),
                    DateTime::fromIsoString($entry->updated)
                )
            );
        }

        return $result;
    }

    private function buildParameters(
        DateTime $threshold,
        $feed,
        $limit,
        $order
    ) {
        if (!is_int($limit) || $limit < 1) {
            throw new InvalidArgumentException('Limit must be a positive integer.');
        } elseif ($order === SORT_ASC) {
            $order = 'asc';
        } elseif ($order === SORT_DESC) {
            $order = 'desc';
        } else {
            throw new InvalidArgumentException('Sort order must be SORT_ASC or SORT_DESC.');
        }

        $parameters = [
            'newerThan' => $threshold->isoString(),
            'maxCount'  => $limit,
            'order'     => $order,
        ];

        if ($feed) {
            $parameters['feed'] = $feed;
        }

        return $parameters;
    }

    private $xmlReader;
}
