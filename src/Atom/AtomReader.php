<?php
namespace Icecave\Siphon\Atom;

use Icecave\Chrono\DateTime;
use Icecave\Siphon\XmlReaderInterface;
use InvalidArgumentException;

class AtomReader implements AtomReaderInterface
{
    public function __construct(XmlReaderInterface $xmlReader)
    {
        $this->xmlReader = $xmlReader;
    }

    /**
     * Fetch a list of feeds that have been updated since the given time point.
     *
     * @param DateTime    $threshold Feeds updated after this time point are included in the result.
     * @param string|null $feed      Limit results to feeds of the given type, or null for any type.
     * @param integer     $limit     The maximum number of results to return.
     * @param integer     $order     The sort order (one of SORT_ASC or SORT_DESC).
     *
     * @return AtomResult
     */
    public function read(
        DateTime $threshold,
        $feed = null,
        $limit = 5000,
        $order = SORT_ASC
    ) {
        $xml = $this->xmlReader->read(
            'Atom',
            $this->buildParameters(
                $threshold,
                $feed,
                $limit,
                $order
            )
        );

        $result = new AtomResult;

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
