<?php
namespace Icecave\Siphon\Score\BoxScore;

use Icecave\Siphon\Player\PlayerFactoryTrait;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\XmlReaderInterface;
use Icecave\Siphon\Schedule\CompetitionFactoryTrait;
use Icecave\Siphon\Schedule\SeasonFactoryTrait;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Statistics\StatisticsFactoryTrait;
use Icecave\Siphon\Team\TeamFactoryTrait;
use Icecave\Siphon\Util\XPath;
use InvalidArgumentException;

/**
 * Client for reading live score feeds.
 */
class BoxScoreReader implements BoxScoreReaderInterface
{
    use CompetitionFactoryTrait;
    use PlayerFactoryTrait;
    use SeasonFactoryTrait;
    use StatisticsFactoryTrait;
    use TeamFactoryTrait;

    /**
     * @param XMLReaderInterface $xmlReader
     */
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
        }

        $xml = $this
            ->xmlReader
            ->read(
                sprintf(
                    '/sport/v2/%s/%s/boxscores/%s/boxscore_%s_%d.xml',
                    $request->sport()->sport(),
                    $request->sport()->league(),
                    $request->seasonName(),
                    $request->sport()->league(),
                    $request->competitionId()
                )
            )
            ->xpath('.//season-content')[0];

        $competition = $this->createCompetition(
            $xml->competition,
            $request->sport(),
            $this->createSeason(
                $xml->season
            )
        );

        $response = new BoxScoreResponse(
            $competition,
            $this->createStatisticsCollection($xml->competition->{'home-team-content'}),
            $this->createStatisticsCollection($xml->competition->{'away-team-content'})
        );

        $qaStatus = XPath::stringOrNull($xml, ".//competition/meta/property[@name='qa-status']");

        if ('finalized' === $qaStatus) {
            $response->setIsFinalized(true);
        }

        foreach ($xml->xpath('.//player-content') as $element) {
            $response->add(
                $this->createPlayer($element->player),
                $this->createStatisticsCollection($element)
            );
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
        return $request instanceof BoxScoreRequest;
    }

    private $xmlReader;
}
