<?php

namespace Icecave\Siphon\Reader;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Siphon\Reader\Exception\NotFoundException;
use React\Promise;

trait XmlReaderTestTrait
{
    protected function setUpXmlReader($path)
    {
        $this->xmlReader()->read->returns(
            Promise\resolve(simplexml_load_string(file_get_contents(__DIR__ . '/../../fixture/' . $path)))
        );
    }

    protected function setUpXmlReaderNotFound()
    {
        $this->xmlReader()->read->returns(Promise\reject(new NotFoundException()));
    }

    protected function urlBuilder()
    {
        if (null === $this->urlBuilder) {
            $this->urlBuilder = new RequestUrlBuilder(new UrlBuilder('xxx', 'http://sdi.example'));
        }

        return $this->urlBuilder;
    }

    protected function xmlReader()
    {
        if (null === $this->xmlReader) {
            $this->xmlReader = Phony::mock(XmlReaderInterface::class);
        }

        return $this->xmlReader;
    }

    private $urlBuilder;
    private $xmlReader;
}
