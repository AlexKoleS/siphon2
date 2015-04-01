<?php
namespace Icecave\Siphon\Player;

use Icecave\Chrono\Date;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Atom\AtomEntry;
use Icecave\Siphon\Util;
use Icecave\Siphon\XPath;
use Icecave\Siphon\XmlReaderInterface;
use InvalidArgumentException;

/**
 * Read data from player injury feeds.
 */
class InjuryReader implements InjuryReaderInterface
{
    public function __construct(XmlReaderInterface $xmlReader)
    {
        $this->xmlReader = $xmlReader;
    }

    /**
     * Read a player injury feed.
     *
     * @param string $sport  The sport (eg, baseball, football, etc)
     * @param string $league The league (eg, MLB, NFL, etc)
     *
     * @return array<InjuryInterface>
     */
    public function read($sport, $league)
    {
        $sport  = strtolower($sport);
        $league = strtoupper($league);

        $xml = $this
            ->xmlReader
            ->read(
                sprintf(
                    self::URL_PATTERN,
                    $sport,
                    $league,
                    $league
                )
            )
            ->xpath('//player-content');

        $result = [];

        foreach ($xml as $element) {
            $injury = $element->{'injury'};

            if ($injury->{'last-updated'}) {
                $updatedTime = DateTime::fromIsoString(
                    strval($injury->{'last-updated'})
                );
            } else {
                $updatedTime = null;
            }

            $result[] = new Injury(
                strval($injury->id),
                strval($element->{'player'}->id),
                strval($injury->location->name),
                InjuryStatus::memberByValue(
                    strval($injury->{'injury-status'}->status)
                ),
                strval($injury->{'injury-status'}->{'display-status'}),
                strval($injury->{'injury-status'}->note),
                Date::fromIsoString($injury->{'start-date'}),
                $updatedTime
            );
        }

        return $result;
    }

    /**
     * Read a feed based on an atom entry.
     *
     * @param AtomEntry $atomEntry
     *
     * @return array<InjuryInterface>
     * @throws InvalidArgumentException if this atom entry is not supported.
     */
    public function readAtomEntry(AtomEntry $atomEntry)
    {
        if (!$this->supportsAtomEntry($atomEntry)) {
            throw new InvalidArgumentException(
                'Unsupported atom entry.'
            );
        }

        list($sport, $league) = Util::parse(
            self::URL_PATTERN,
            $atomEntry->resource()
        );

        return $this->read($sport, $league);
    }

    /**
     * Check if the given atom entry can be used by this reader.
     *
     * @param AtomEntry $atomEntry
     *
     * @return boolean
     */
    public function supportsAtomEntry(AtomEntry $atomEntry)
    {
        if ($atomEntry->parameters()) {
            return false;
        }

        return null !== Util::parse(
            self::URL_PATTERN,
            $atomEntry->resource()
        );
    }

    const URL_PATTERN = '/sport/v2/%s/%s/injuries/injuries_%s.xml';

    private $xmlReader;
}
