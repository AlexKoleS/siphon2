<?php

namespace Icecave\Siphon\Atom;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\XmlReaderTestTrait;
use PHPUnit_Framework_TestCase;

class AtomReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->modifiedTime = DateTime::fromUnixTime(43200);
        $this->setUpXmlReader('Atom/atom.xml', $this->modifiedTime);

        $this->request = new AtomRequest(DateTime::fromUnixTime(86400));
        $this->reader = new AtomReader($this->urlBuilder(), $this->xmlReader()->mock());

        $this->resolve = Phony::spy();
        $this->reject = Phony::spy();
    }

    public function testRead()
    {
        $this->reader->read($this->request)->done($this->resolve, $this->reject);

        $this->xmlReader->read->calledWith(
            'http://sdi.example/Atom?apiKey=xxx&newerThan=1970-01-02T00%3A00%3A00%2B00%3A00&maxCount=5000&order=asc'
        );
        $this->reject->never()->called();
        $response = $this->resolve->calledWith($this->isInstanceOf(AtomResponse::class))->argument();

        $this->assertEquals('2015-02-15T21:11:15.4952-04:00', $response->updatedTime());
        $this->assertEquals(
            [
                [
                    'http://xml.sportsdirectinc.com/sport/v2/hockey/NHL/livescores/livescores_64109.xml',
                    DateTime::fromIsoString('2015-02-15T21:11:11.4811-04:00'),
                ],
                [
                    'http://xml.sportsdirectinc.com/sport/v2/hockey/NHL/livescores/livescores_64110.xml',
                    DateTime::fromIsoString('2015-02-15T21:11:11.5121-04:00'),
                ],
                [
                    'http://xml.sportsdirectinc.com/sport/v2/hockey/NHL/livescores/livescores_64108.xml',
                    DateTime::fromIsoString('2015-02-15T21:11:14.4951-04:00'),
                ],
            ],
            iterator_to_array($response)
        );
        $this->assertSame($this->modifiedTime, $response->modifiedTime());
    }

    public function testReadWithUnsupportedRequest()
    {
        $this->setExpectedException('InvalidArgumentException', 'Unsupported request.');

        $this->reader->read(Phony::mock(RequestInterface::class)->mock())->done();
    }

    public function testIsSupported()
    {
        $this->assertTrue($this->reader->isSupported($this->request));
        $this->assertFalse($this->reader->isSupported(Phony::mock(RequestInterface::class)->mock()));
    }
}
