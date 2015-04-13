<?php
namespace Icecave\Siphon;

use Eloquent\Phony\Phpunit\Phony;
use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Message\ResponseInterface;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Atom\AtomEntry;
use Icecave\Siphon\Exception\ServiceUnavailableException;
use PHPUnit_Framework_TestCase;

class XmlReaderTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->urlBuilder = Phony::mock(UrlBuilderInterface::class);
        $this->httpClient = Phony::mock(ClientInterface::class);
        $this->response   = Phony::mock(ResponseInterface::class);

        $this
            ->urlBuilder
            ->build
            ->returns('<url>');

        $this
            ->httpClient
            ->get
            ->returns($this->response->mock());

        $this
            ->response
            ->xml
            ->returns('<xml>');

        $this->reader = new XmlReader(
            $this->urlBuilder->mock(),
            $this->httpClient->mock()
        );
    }

    public function testRead()
    {
        $result = $this->reader->read(
            'path/to/feed',
            ['foo' => 'bar']
        );

        $this
            ->urlBuilder
            ->build
            ->calledWith(
                'path/to/feed',
                ['foo' => 'bar']
            );

        $this
            ->httpClient
            ->get
            ->calledWith('<url>');

        $this->assertEquals(
            '<xml>',
            $result
        );
    }

    public function testReadWithHttpClientException()
    {
        $exception = new Exception('The exception!');

        $this
            ->httpClient
            ->get
            ->throws($exception);

        $this->setExpectedException(
            ServiceUnavailableException::class,
            'The exception!'
        );

        $this->reader->read(
            'path/to/feed'
        );
    }

    public function testReadAtomEntry()
    {
        $entry = new AtomEntry(
            '<atom-url>',
            '<atom-resource>',
            ['foo' => 'bar'],
            DateTime::fromUnixTime(0)
        );

        $result = $this->reader->readAtomEntry($entry);

        $this
            ->urlBuilder
            ->build
            ->calledWith(
                '<atom-resource>',
                ['foo' => 'bar']
            );

        $this
            ->httpClient
            ->get
            ->calledWith('<url>');

        $this->assertEquals(
            '<xml>',
            $result
        );
    }

    public function testSupportsAtomEntry()
    {
        $entry = new AtomEntry(
            '<atom-url>',
            '<atom-resource>',
            ['foo' => 'bar'],
            DateTime::fromUnixTime(0)
        );

        $this->assertTrue(
            $this->reader->supportsAtomEntry($entry)
        );
    }

    public function testSupportsAtomEntryParameters()
    {
        $entry = new AtomEntry(
            '<atom-url>',
            '<atom-resource>',
            ['foo' => 'bar'],
            DateTime::fromUnixTime(0)
        );

        $parameters = [];

        $this->assertTrue(
            $this->reader->supportsAtomEntry($entry, $parameters)
        );

        $this->assertSame(
            [],
            $parameters
        );
    }
}
