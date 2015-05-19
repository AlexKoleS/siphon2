<?php
namespace Icecave\Siphon\Reader;

use Eloquent\Phony\Phpunit\Phony;
use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Message\RequestInterface as HttpRequestInterface;
use GuzzleHttp\Message\ResponseInterface as HttpResponseInterface;
use Icecave\Siphon\Reader\Exception\NotFoundException;
use Icecave\Siphon\Reader\Exception\ServiceUnavailableException;
use PHPUnit_Framework_TestCase;

class XmlReaderTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->urlBuilder = Phony::mock(UrlBuilderInterface::class);
        $this->httpClient = Phony::mock(ClientInterface::class);
        $this->response   = Phony::mock(HttpResponseInterface::class);

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
        $exception = Phony::fullMock(ClientException::class)->mock();

        $this
            ->httpClient
            ->get
            ->throws($exception);

        $this->setExpectedException(
            ServiceUnavailableException::class,
            'Service unavailable.'
        );

        $this->reader->read(
            'path/to/feed'
        );
    }

    public function testReadWithNotFoundException()
    {
        $this
            ->response
            ->getStatusCode
            ->returns(404);

        $exception = new ClientException(
            '<message>',
            Phony::mock(HttpRequestInterface::class)->mock(),
            $this->response->mock()
        );

        $this
            ->httpClient
            ->get
            ->throws($exception);

        $this->setExpectedException(
            NotFoundException::class,
            'Feed not found.'
        );

        $this->reader->read(
            'path/to/feed'
        );
    }

    public function testReadWithGenericException()
    {
        $exception = new Exception('The exception!');

        $this
            ->httpClient
            ->get
            ->throws($exception);

        $this->setExpectedException(
            ServiceUnavailableException::class,
            'Service unavailable.'
        );

        $this->reader->read(
            'path/to/feed'
        );
    }
}
