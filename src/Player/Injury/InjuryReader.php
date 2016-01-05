<?php

namespace Icecave\Siphon\Player\Injury;

use Icecave\Chrono\Date;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Player\PlayerFactoryTrait;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\RequestUrlBuilderInterface;
use Icecave\Siphon\Reader\XmlReaderInterface;
use Icecave\Siphon\Util\XPath;
use InvalidArgumentException;
use React\Promise;

/**
 * Client for reading player injury feeds.
 */
class InjuryReader implements InjuryReaderInterface
{
    use PlayerFactoryTrait;

    public function __construct(
        RequestUrlBuilderInterface $urlBuilder,
        XmlReaderInterface $xmlReader
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->xmlReader = $xmlReader;
    }

    /**
     * Make a request and return the response.
     *
     * @param RequestInterface The request.
     *
     * @return ResponseInterface        [via promise] The response.
     * @throws InvalidArgumentException [via promise] If the request is not supported.
     */
    public function read(RequestInterface $request)
    {
        if (!$this->isSupported($request)) {
            return Promise\reject(
                new InvalidArgumentException('Unsupported request.')
            );
        }

        return $this->xmlReader->read($this->urlBuilder->build($request))->then(
            function ($result) use ($request) {
                list($xml, $modifiedTime) = $result;
                $xml = $xml->xpath('.//player-content');
                $response = new InjuryResponse($request->sport());
                $response->setModifiedTime($modifiedTime);

                foreach ($xml as $element) {
                    $injury = $element->injury;
                    $status = $injury->{'injury-status'};

                    if ($injury->{'last-updated'}) {
                        $updatedTime = DateTime::fromIsoString(
                            strval($injury->{'last-updated'})
                        );
                    } else {
                        $updatedTime = null;
                    }

                    $response->add(
                        $this->createPlayer($element->player),
                        new Injury(
                            strval($injury->id),
                            strval($injury->location->name),
                            InjuryStatus::memberByValue(
                                strval($status->status)
                            ),
                            strval($status->{'display-status'}),
                            strval($status->note),
                            Date::fromIsoString($injury->{'start-date'}),
                            $updatedTime
                        )
                    );
                }

                return $response;
            }
        );
    }

    /**
     * Check if the given request is supported.
     *
     * @return boolean True if the given request is supported; otherwise, false.
     */
    public function isSupported(RequestInterface $request)
    {
        return $request instanceof InjuryRequest;
    }

    private $urlBuilder;
    private $xmlReader;
}
