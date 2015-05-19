<?php
namespace Icecave\Siphon\Schedule;

use Icecave\Chrono\Date;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Player\Player;
use Icecave\Siphon\Player\PlayerFactoryTrait;
use Icecave\Siphon\Reader\Exception\NotFoundException;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\XmlReaderInterface;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Team\TeamRef;
use InvalidArgumentException;
use SimpleXMLElement;

/**
 * Client for reading schedule feeds.
 */
class ScheduleReader implements ScheduleReaderInterface
{
    use PlayerFactoryTrait;

    public function __construct(XmlReaderInterface $xmlReader)
    {
        $this->xmlReader = $xmlReader;
    }

    /**
     * Make a request and return the response.
     *
     * @param RequestInterface The request.
     *
     * @return ResponseInterface        The response.
     * @throws InvalidArgumentException if the request is not supported.
     */
    public function read(RequestInterface $request)
    {
        if (!$this->isSupported($request)) {
            throw new InvalidArgumentException('Unsupported request.');
        } elseif (ScheduleType::FULL() === $request->type()) {
            $resource = sprintf(
                '/sport/v2/%s/%s/schedule/schedule_%s.xml',
                $request->sport()->sport(),
                $request->sport()->league(),
                $request->sport()->league()
            );
        } elseif (ScheduleType::DELETED() === $request->type()) {
            $resource = sprintf(
                '/sport/v2/%s/%s/games-deleted/games_deleted_%s.xml',
                $request->sport()->sport(),
                $request->sport()->league(),
                $request->sport()->league()
            );
        } else {
            $resource = sprintf(
                '/sport/v2/%s/%s/schedule/schedule_%s_%d_days.xml',
                $request->sport()->sport(),
                $request->sport()->league(),
                $request->sport()->league(),
                $request->type()->value()
            );
        }

        $response = new ScheduleResponse(
            $request->sport(),
            $request->type()
        );

        try {
            $xml = $this
                ->xmlReader
                ->read($resource)
                ->xpath('.//season-content');
        } catch (NotFoundException $e) {
            // If a well-formed request is not found it appears to means there
            // are no upcoming seasons within the timeframe given by the request's
            // schedule type.
            return $response;
        }

        foreach ($xml as $element) {
            $season = $this->createSeason($element->season);

            foreach ($element->competition as $competitionElement) {
                $season->add(
                    $this->createCompetition(
                        $competitionElement,
                        $request->sport(),
                        $season
                    )
                );
            }

            $response->add($season);
        }

        return $response;
    }

    /**
     * Check if the given request is supported.
     *
     * @return boolean True if the given request is supported; otherwise, false.
     */
    public function isSupported(RequestInterface $request)
    {
        return $request instanceof ScheduleRequest;
    }

    private function createSeason(SimpleXMLElement $element)
    {
        return new Season(
            strval($element->id),
            strval($element->name),
            Date::fromIsoString(
                $element->details->{'start-date'}
            ),
            Date::fromIsoString(
                $element->details->{'end-date'}
            )
        );
    }

    private function createCompetition(
        SimpleXMLElement $element,
        Sport $sport,
        SeasonInterface $season
    ) {
        $competition = new Competition(
            strval($element->id),
            CompetitionStatus::memberByValue(
                strval($element->{'result-scope'}->{'competition-status'})
            ),
            DateTime::fromIsoString(
                $element->{'start-date'}
            ),
            $sport,
            $season,
            $this->createTeamReference(
                $element->{'home-team-content'}->{'team'}
            ),
            $this->createTeamReference(
                $element->{'away-team-content'}->{'team'}
            )
        );

        foreach ($element->xpath('.//player') as $playerElement) {
            $competition->addNotablePlayer(
                $this->createPlayer($playerElement)
            );
        }

        return $competition;
    }

    private function createTeamReference(SimpleXMLElement $element)
    {
        return new TeamRef(
            strval($element->id),
            strval($element->name)
        );
    }

    private $xmlReader;
}
