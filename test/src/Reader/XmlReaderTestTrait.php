<?php
namespace Icecave\Siphon\Reader;

use Eloquent\Phony\Phpunit\Phony;

trait XmlReaderTestTrait
{
    public function setUpXmlReader($path)
    {
        $this
            ->xmlReader()
            ->read
            ->returns(
                simplexml_load_string(
                    file_get_contents(__DIR__ . '/../../fixture/' . $path)
                )
            );
    }

    public function xmlReader()
    {
        if (null === $this->xmlReader) {
            $this->xmlReader = Phony::mock(XmlReaderInterface::class);
        }

        return $this->xmlReader;
    }

    private $xmlReader;
}
