<?php
namespace Icecave\Siphon;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Siphon\Atom\AtomReader;
use Icecave\Siphon\Atom\AtomRequest;
use Icecave\Siphon\Atom\AtomResponse;
use Icecave\Siphon\Reader\UrlBuilderInterface;
use Icecave\Siphon\Reader\XmlReaderInterface;
use Icecave\Siphon\Schedule\ScheduleReader;
use Icecave\Siphon\Schedule\ScheduleRequest;
use Icecave\Siphon\Schedule\ScheduleResponse;
use PHPUnit_Framework_TestCase;

class DispatcherTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->urlBuilder     = Phony::mock(UrlBuilderInterface::class);
        $this->xmlReader      = Phony::mock(XmlReaderInterface::class);
        $this->atomReader     = Phony::fullMock(AtomReader::class);
        $this->scheduleReader = Phony::fullMock(ScheduleReader::class);

        $this->dispatcher = new Dispatcher(
            $this->urlBuilder->mock(),
            $this->xmlReader->mock(),
            $this->atomReader->mock(),
            $this->scheduleReader->mock()
        );
    }

    public function testCreate()
    {
        $this->dispatcher = Dispatcher::create('<api key>');

        $this->assertInstanceOf(
            DispatcherInterface::class,
            $this->dispatcher
        );

        $this->assertContains(
            '%3Capi+key%3E',
            $this->dispatcher->urlBuilder()->build('/')
        );
    }

    public function testUrlBuilder()
    {
        $this->assertSame(
            $this->urlBuilder->mock(),
            $this->dispatcher->urlBuilder()
        );
    }

    public function testXmlReader()
    {
        $this->assertSame(
            $this->xmlReader->mock(),
            $this->dispatcher->xmlReader()
        );
    }

    public function testReadAtomRequest()
    {
        $this->dispatchTest(
            AtomRequest::class,
            AtomResponse::class,
            $this->atomReader
        );
    }

    public function testScheduleRequest()
    {
        $this->dispatchTest(
            ScheduleRequest::class,
            ScheduleResponse::class,
            $this->scheduleReader
        );
    }

    private function dispatchTest(
        $requestClass,
        $responseClass,
        $reader
    ) {
        $request  = Phony::fullMock($requestClass);
        $response = Phony::fullMock($responseClass);

        $request
            ->accept
            ->forwards();

        $reader
            ->read
            ->returns($response->mock());

        $result = $this
            ->dispatcher
            ->read(
                $request->mock()
            );

        $reader
            ->read
            ->calledWith(
                $this->identicalTo($request->mock())
            );

        $this->assertSame(
            $response->mock(),
            $result
        );

        $this->assertTrue(
            $this->dispatcher->isSupported(
                $request->mock()
            )
        );
    }
}
