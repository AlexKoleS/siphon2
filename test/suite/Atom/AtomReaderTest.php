<?php
namespace Icecave\Siphon\Atom;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Reader\RequestFactoryInterface;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\XmlReaderTestTrait;
use PHPUnit_Framework_TestCase;

class AtomReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->setUpXmlReader('Atom/atom.xml');

        $this->requestFactory = Phony::mock(RequestFactoryInterface::class);
        $this->request1       = Phony::mock(RequestInterface::class);
        $this->request2       = Phony::mock(RequestInterface::class);
        $this->request3       = Phony::mock(RequestInterface::class);

        $this
            ->requestFactory
            ->create
            ->returns($this->request1->mock())
            ->returns($this->request2->mock())
            ->returns($this->request3->mock());

        $this->request = new AtomRequest(
            DateTime::fromUnixTime(86400)
        );

        $this->reader = new AtomReader(
            $this->xmlReader()->mock(),
            $this->requestFactory->mock()
        );
    }

    public function testRead()
    {
        $response = $this
            ->reader
            ->read($this->request);

        $this
            ->xmlReader
            ->read
            ->calledWith(
                '/Atom',
                [
                    'newerThan' => '1970-01-02T00:00:00+00:00',
                    'maxCount'  => 5000,
                    'order'     => 'asc',
                ]
            );

        $this->assertInstanceOf(
            AtomResponse::class,
            $response
        );

        $this->assertEquals(
            DateTime::fromIsoString('2015-02-15T21:11:15.4952-04:00'),
            $response->updatedTime()
        );

        $this->assertSame(
            [
                $this->request1->mock(),
                $this->request2->mock(),
                $this->request3->mock(),
            ],
            iterator_to_array($response)
        );
    }

    public function testReadWithSpecificFeed()
    {
        $this->request->setFeed('/foo');

        $response = $this
            ->reader
            ->read($this->request);

        $this
            ->xmlReader
            ->read
            ->calledWith(
                '/Atom',
                [
                    'newerThan' => '1970-01-02T00:00:00+00:00',
                    'maxCount'  => 5000,
                    'order'     => 'asc',
                    'feed'      => '/foo'
                ]
            );
    }

    public function testReadWithLimit()
    {
        $this->request->setLimit(100);

        $response = $this
            ->reader
            ->read($this->request);

        $this
            ->xmlReader
            ->read
            ->calledWith(
                '/Atom',
                [
                    'newerThan' => '1970-01-02T00:00:00+00:00',
                    'maxCount'  => 100,
                    'order'     => 'asc',
                ]
            );
    }

    public function testReadDescending()
    {
        $this->request->setOrder(SORT_DESC);

        $response = $this
            ->reader
            ->read($this->request);

        $this
            ->xmlReader
            ->read
            ->calledWith(
                '/Atom',
                [
                    'newerThan' => '1970-01-02T00:00:00+00:00',
                    'maxCount'  => 5000,
                    'order'     => 'desc',
                ]
            );
    }

    public function testReadWithUnsupportedRequest()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Unsupported request.'
        );

        $this->reader->read($this->request1->mock());
    }

    public function testIsSupported()
    {
        $this->assertTrue(
            $this->reader->isSupported($this->request)
        );

        $this->assertFalse(
            $this->reader->isSupported($this->request1->mock())
        );
    }
}
