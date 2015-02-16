<?php
namespace Icecave\Siphon;

use Eloquent\Phony\Phpunit\Phony;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Message\ResponseInterface;
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
}
