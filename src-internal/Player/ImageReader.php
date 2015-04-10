<?php
namespace Icecave\Siphon\Player;

use Icecave\Siphon\Atom\AtomEntry;
use Icecave\Siphon\Util;
use Icecave\Siphon\XPath;
use Icecave\Siphon\XmlReaderInterface;
use InvalidArgumentException;

/**
 * Read data from player image feeds.
 */
class ImageReader implements PlayerReaderInterface
{
    public function __construct(XmlReaderInterface $xmlReader)
    {
        $this->xmlReader = $xmlReader;
    }

    /**
     * Read a player image feed.
     *
     * @param string $sport  The sport (eg, baseball, football, etc)
     * @param string $league The league (eg, MLB, NFL, etc)
     * @param string $season The season name.
     * @param string $teamId The ID of the team.
     *
     * @return array<ImageInterface>
     */
    public function read($sport, $league, $season, $teamId)
    {
        return $this->readImpl(
            $sport,
            $league,
            $season,
            Util::extractNumericId($teamId)
        );
    }

    /**
     * Read a feed based on an atom entry.
     *
     * @param AtomEntry $atomEntry
     *
     * @return mixed
     */
    public function readAtomEntry(AtomEntry $atomEntry)
    {
        if (!$this->supportsAtomEntry($atomEntry)) {
            throw new InvalidArgumentException(
                'Unsupported atom entry.'
            );
        }

        list($sport, $league, $season, $teamId) = Util::parse(
            self::URL_PATTERN,
            $atomEntry->resource()
        );

        return $this->readImpl($sport, $league, $season, $teamId);
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

    /**
     * Read a player image feed.
     *
     * @param string  $sport  The sport (eg, baseball, football, etc)
     * @param string  $league The league (eg, MLB, NFL, etc)
     * @param string  $season The season name.
     * @param integer $teamId The numeric ID of the team.
     *
     * @return array<ImageInterface>
     */
    private function readImpl($sport, $league, $season, $teamId)
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
                    $season,
                    $teamId,
                    $league
                )
            )
            ->xpath('//player-content');

        $result = [];

        foreach ($xml as $element) {
            $result[] = new Image(
                XPath::string($element, 'player/id'),
                XPath::string($element, 'image/url'),
                XPath::string($element, 'image/thumbnailurl')
            );
        }

        return $result;
    }

    const URL_PATTERN = '/sport/v2/%s/%s/player-images/%s/player-images_%d_%s.xml';

    private $xmlReader;
}
