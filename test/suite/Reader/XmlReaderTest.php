<?php

namespace Icecave\Siphon\Reader;

use Clue\React\Buzz\Browser;
use Clue\React\Buzz\Message\Response;
use Clue\React\Buzz\Message\ResponseException;
use Eloquent\Phony\Phpunit\Phony;
use Exception;
use Icecave\Siphon\Reader\Exception\NotFoundException;
use Icecave\Siphon\Reader\Exception\ServiceUnavailableException;
use PHPUnit_Framework_TestCase;
use React\Promise;
use SimpleXMLElement;

class XmlReaderTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->urlBuilder = Phony::mock(UrlBuilderInterface::class);
        $this->httpClient = Phony::mock(Browser::class);
        $this->response   = Phony::mock(Response::class);

        $this->urlBuilder->build->returns('<url>');
        $this->httpClient->get->returns(Promise\resolve($this->response->mock()));
        $this->response->getBody->returns('<xml></xml>');

        $this->reader = new XmlReader($this->urlBuilder->mock(), $this->httpClient->mock());

        $this->resolve = Phony::spy();
        $this->reject = Phony::spy();
    }

    public function testRead()
    {
        $this->reader->read('path/to/feed', ['foo' => 'bar'])->done($this->resolve, $this->reject);

        $this->urlBuilder->build->calledWith('path/to/feed', ['foo' => 'bar']);
        $this->httpClient->get->calledWith('<url>');
        $this->resolve->calledWith(new SimpleXMLElement('<xml></xml>', LIBXML_NONET));
        $this->reject->never()->called();
    }

    public function testReadWithHttpClientException()
    {
        $exception = new ResponseException($this->response->mock());
        $this->httpClient->get->returns(Promise\reject($exception));
        $this->reader->read('path/to/feed')->done($this->resolve, $this->reject);

        $this->reject->calledWith(new ServiceUnavailableException($exception));
        $this->resolve->never()->called();
    }

    public function testReadWithNotFoundException()
    {
        $exception = new ResponseException($this->response->mock());
        $this->response->getCode->returns(404);
        $this->httpClient->get->returns(Promise\reject($exception));
        $this->reader->read('path/to/feed')->done($this->resolve, $this->reject);

        $this->reject->calledWith(new NotFoundException($exception));
        $this->resolve->never()->called();
    }

    public function testReadWithGenericException()
    {
        $exception = new Exception('The exception!');
        $this->httpClient->get->returns(Promise\reject($exception));
        $this->reader->read('path/to/feed')->done($this->resolve, $this->reject);

        $this->reject->calledWith(new ServiceUnavailableException($exception));
        $this->resolve->never()->called();
    }

    public function testXmlParseErrorWithServiceUnavailableException()
    {
        $this->response->getBody->returns('');
        $this->reader->read('path/to/feed')->done($this->resolve, $this->reject);

        $this->reject->calledWith($this->isInstanceOf(ServiceUnavailableException::class));
        $this->resolve->never()->called();
    }
}
