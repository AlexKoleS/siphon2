<?php

namespace Icecave\Siphon\Reader;

use Eloquent\Phony\Phpunit\Phony;
use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\RejectedPromise;
use Icecave\Siphon\Reader\Exception\NotFoundException;
use Icecave\Siphon\Reader\Exception\ServiceUnavailableException;
use PHPUnit_Framework_TestCase;
use Psr\Http\Message\RequestInterface as HttpRequestInterface;
use Psr\Http\Message\ResponseInterface as HttpResponseInterface;
use SimpleXMLElement;

class XmlReaderTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->urlBuilder = Phony::mock(UrlBuilderInterface::class);
        $this->httpClient = Phony::mock(ClientInterface::class);
        $this->response   = Phony::mock(HttpResponseInterface::class);

        $this->urlBuilder->build->returns('<url>');
        $this->httpClient->requestAsync->returns(new FulfilledPromise($this->response->mock()));
        $this->response->getBody->returns('<xml></xml>');

        $this->reader = new XmlReader($this->urlBuilder->mock(), $this->httpClient->mock());

        $this->resolve = Phony::spy();
        $this->reject = Phony::spy();
    }

    public function testRead()
    {
        $this->reader->read('path/to/feed', ['foo' => 'bar'])->done($this->resolve, $this->reject);

        Promise\queue()->run();

        $this->urlBuilder->build->calledWith('path/to/feed', ['foo' => 'bar']);
        $this->httpClient->requestAsync->calledWith('GET', '<url>');
        $this->resolve->calledWith(new SimpleXMLElement('<xml></xml>', LIBXML_NONET));
        $this->reject->never()->called();
    }

    public function testReadWithHttpClientException()
    {
        $exception = Phony::mock(ClientException::class)->mock();
        $this->httpClient->requestAsync->returns(new RejectedPromise($exception));
        $this->reader->read('path/to/feed')->done($this->resolve, $this->reject);

        Promise\queue()->run();

        $this->reject->calledWith(new ServiceUnavailableException($exception));
        $this->resolve->never()->called();
    }

    public function testReadWithNotFoundException()
    {
        $this->response->getStatusCode->returns(404);
        $exception = new ClientException(
            '<message>',
            Phony::mock(HttpRequestInterface::class)->mock(),
            $this->response->mock()
        );
        $this->httpClient->requestAsync->returns(new RejectedPromise($exception));
        $this->reader->read('path/to/feed')->done($this->resolve, $this->reject);

        Promise\queue()->run();

        $this->reject->calledWith(new NotFoundException($exception));
        $this->resolve->never()->called();
    }

    public function testReadWithGenericException()
    {
        $exception = new Exception('The exception!');
        $this->httpClient->requestAsync->returns(new RejectedPromise($exception));
        $this->reader->read('path/to/feed')->done($this->resolve, $this->reject);

        Promise\queue()->run();

        $this->reject->calledWith(new ServiceUnavailableException($exception));
        $this->resolve->never()->called();
    }

    public function testXmlParseErrorWithServiceUnavailableException()
    {
        $this->response->getBody->returns('');
        $this->reader->read('path/to/feed')->done($this->resolve, $this->reject);

        Promise\queue()->run();

        $this->reject->calledWith($this->isInstanceOf(ServiceUnavailableException::class));
        $this->resolve->never()->called();
    }
}
