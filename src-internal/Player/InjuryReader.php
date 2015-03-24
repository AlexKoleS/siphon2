<?php
namespace Icecave\Siphon\Player;

use Icecave\Chrono\Date;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\XPath;
use Icecave\Siphon\XmlReaderInterface;

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
                    '/sport/v2/%s/%s/injuries/injuries_%s.xml',
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

    private $xmlReader;
}
