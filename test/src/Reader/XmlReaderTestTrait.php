<?php

namespace Icecave\Siphon\Reader;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Siphon\Reader\Exception\NotFoundException;
use React\Promise;

trait XmlReaderTestTrait
{
    public function setUpXmlReader($path)
    {
        $this->xmlReader()->read->returns(
            Promise\resolve(simplexml_load_string(file_get_contents(__DIR__ . '/../../fixture/' . $path)))
        );
    }

    public function setUpXmlReaderNotFound()
    {
        $this->xmlReader()->read->returns(Promise\reject(new NotFoundException()));
    }

    public function urlBuilder()
    {
        if (null === $this->urlBuilder) {
            $this->urlBuilder = new UrlBuilder('00000000-0000-0000-0000-000000000000', 'http://sdi.example.org');
        }

        return $this->urlBuilder;
    }

    public function xmlReader()
    {
        if (null === $this->xmlReader) {
            $this->xmlReader = Phony::mock(XmlReaderInterface::class);
        }

        return $this->xmlReader;
    }

    private $urlBuilder;
    private $xmlReader;
}
