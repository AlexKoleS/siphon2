<?php
namespace Icecave\Siphon\Reader;

use Eloquent\Phony\Phpunit\Phony;
use PHPUnit_Framework_TestCase;

class CompositeReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->reader1   = Phony::mock(ReaderInterface::class);
        $this->reader2   = Phony::mock(ReaderInterface::class);
        $this->request1  = Phony::mock(RequestInterface::class);
        $this->request2  = Phony::mock(RequestInterface::class);
        $this->response1 = Phony::mock(ResponseInterface::class);
        $this->response2 = Phony::mock(ResponseInterface::class);

        // reader 1
        $this
            ->reader1
            ->isSupported
            ->with($this->identicalTo($this->request1->mock()))
            ->returns(true);

        $this
            ->reader1
            ->isSupported
            ->with($this->identicalTo($this->request2->mock()))
            ->returns(false);

        $this
            ->reader1
            ->read
            ->with($this->identicalTo($this->request1->mock()))
            ->returns($this->response1->mock());

        // reader 2
        $this
            ->reader2
            ->isSupported
            ->with($this->identicalTo($this->request1->mock()))
            ->returns(false);

        $this
            ->reader2
            ->isSupported
            ->with($this->identicalTo($this->request2->mock()))
            ->returns(true);

        $this
            ->reader2
            ->read
            ->with($this->identicalTo($this->request2->mock()))
            ->returns($this->response2->mock());

        $this->reader = new CompositeReader(
            [
                $this->reader1->mock(),
                $this->reader2->mock(),
            ]
        );
    }

    public function testRead()
    {
        $this->assertSame(
            $this->response1->mock(),
            $this->reader->read(
                $this->request1->mock()
            )
        );

        $this->assertSame(
            $this->response2->mock(),
            $this->reader->read(
                $this->request2->mock()
            )
        );
    }

    public function testIsSupported()
    {
        $this->assertTrue(
            $this->reader->isSupported(
                $this->request1->mock()
            )
        );

        $this->assertTrue(
            $this->reader->isSupported(
                $this->request1->mock()
            )
        );

        $this->reader->remove(
            $this->reader1->mock()
        );

        $this->assertFalse(
            $this->reader->isSupported(
                $this->request1->mock()
            )
        );
    }

    public function testReadFailure()
    {
        $this->reader->remove(
            $this->reader1->mock()
        );

        $this->setExpectedException(
            'InvalidArgumentException',
            'Unsupported request.'
        );

        $this->reader->read(
            $this->request1->mock()
        );
    }
}
