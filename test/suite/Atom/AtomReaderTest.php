<?php
namespace Icecave\Siphon\Atom;

use Icecave\Chrono\DateTime;
use Icecave\Siphon\XmlReaderTestTrait;
use PHPUnit_Framework_TestCase;

class AtomReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->setUpXmlReader('Atom/atom.xml');

        $this->threshold = DateTime::fromUnixTime(0);

        $this->reader = new AtomReader(
            $this->xmlReader()->mock()
        );
    }

    public function testRead()
    {
        $result = $this->reader->read(
            $this->threshold
        );

        $this
            ->xmlReader
            ->read
            ->calledWith(
                '/Atom',
                [
                    'newerThan' => '1970-01-01T00:00:00+00:00',
                    'maxCount'  => 5000,
                    'order'     => 'asc',
                ]
            );

        $this->assertInstanceOf(
            AtomResult::class,
            $result
        );

        $this->assertEquals(
            DateTime::fromIsoString('2015-02-15T21:11:15.4952-04:00'),
            $result->updatedTime()
        );

        $this->assertEquals(
            [
                new AtomEntry(
                    'http://xml.sportsdirectinc.com/sport/v2/hockey/NHL/livescores/livescores_64109.xml?apiKey=APIKEY',
                    DateTime::fromIsoString('2015-02-15T21:11:11.4811-04:00')
                ),
                new AtomEntry(
                    'http://xml.sportsdirectinc.com/sport/v2/hockey/NHL/livescores/livescores_64110.xml?apiKey=APIKEY',
                    DateTime::fromIsoString('2015-02-15T21:11:11.5121-04:00')
                ),
                new AtomEntry(
                    'http://xml.sportsdirectinc.com/sport/v2/hockey/NHL/livescores/livescores_64108.xml?apiKey=APIKEY',
                    DateTime::fromIsoString('2015-02-15T21:11:14.4951-04:00')
                ),
            ],
            iterator_to_array($result)
        );
    }

    public function testReadWithSpecificFeed()
    {
        $this->reader->read(
            $this->threshold,
            '/foo'
        );

        $this
            ->xmlReader
            ->read
            ->calledWith(
                '/Atom',
                [
                    'newerThan' => '1970-01-01T00:00:00+00:00',
                    'maxCount'  => 5000,
                    'order'     => 'asc',
                    'feed'      => '/foo'
                ]
            );
    }

    public function testReadWithInvalidLimit()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Limit must be a positive integer.'
        );

        $this->reader->read(
            $this->threshold,
            null,
            -1
        );
    }

    public function testReadOrderAscending()
    {
        $this->reader->read(
            $this->threshold,
            null,
            1,
            SORT_ASC
        );

        $this
            ->xmlReader
            ->read
            ->calledWith(
                '/Atom',
                [
                    'newerThan' => '1970-01-01T00:00:00+00:00',
                    'maxCount'  => 1,
                    'order'     => 'asc',
                ]
            );
    }

    public function testReadOrderDescending()
    {
        $this->reader->read(
            $this->threshold,
            null,
            1,
            SORT_DESC
        );

        $this
            ->xmlReader
            ->read
            ->calledWith(
                '/Atom',
                [
                    'newerThan' => '1970-01-01T00:00:00+00:00',
                    'maxCount'  => 1,
                    'order'     => 'desc',
                ]
            );
    }

    public function testReadWithInvalidOrder()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Sort order must be SORT_ASC or SORT_DESC.'
        );

        $this->reader->read(
            $this->threshold,
            null,
            1,
            '<what>'
        );
    }
}