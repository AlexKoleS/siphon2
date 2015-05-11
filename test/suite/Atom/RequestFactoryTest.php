<?php
namespace Icecave\Siphon\Atom;

use Icecave\Chrono\DateTime;
use PHPUnit_Framework_TestCase;

class RequestFactoryTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->factory = new RequestFactory;
    }

    /**
     * @dataProvider urlTestVectors
     */
    public function testCreate($url, $request)
    {
        $this->assertEquals(
            $request,
            $this->factory->create($url)
        );
    }

    public function testCreateWithUnsupportedUrl()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Unsupported URL.'
        );

        $this->factory->create('<nope>');
    }

    public function urlTestVectors()
    {
        return [
            'atom' => [
                '/Atom?newerThan=2010-01-02T03:04:05Z',
                new AtomRequest(
                    DateTime::fromIsoString('2010-01-02T03:04:05Z')
                ),
            ],
            'atom with feed' => [
                '/Atom?newerThan=2010-01-02T03:04:05Z&feed=/foo',
                new AtomRequest(
                    DateTime::fromIsoString('2010-01-02T03:04:05Z'),
                    '/foo'
                ),
            ],
            'atom with limit' => [
                '/Atom?newerThan=2010-01-02T03:04:05Z&limit=100',
                new AtomRequest(
                    DateTime::fromIsoString('2010-01-02T03:04:05Z'),
                    null,
                    100
                ),
            ],
            'atom with asc order' => [
                '/Atom?newerThan=2010-01-02T03:04:05Z&order=asc',
                new AtomRequest(
                    DateTime::fromIsoString('2010-01-02T03:04:05Z'),
                    null,
                    5000,
                    SORT_ASC
                ),
            ],
            'atom with desc order' => [
                '/Atom?newerThan=2010-01-02T03:04:05Z&order=desc',
                new AtomRequest(
                    DateTime::fromIsoString('2010-01-02T03:04:05Z'),
                    null,
                    5000,
                    SORT_DESC
                ),
            ],
        ];
    }
}
