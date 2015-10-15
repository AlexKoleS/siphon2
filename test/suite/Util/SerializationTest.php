<?php

namespace Icecave\Siphon\Util;

use Eloquent\Phony\Phpunit\Phony;
use PHPUnit_Framework_TestCase;

class SerializationTest extends PHPUnit_Framework_TestCase
{
    public function testSerialize()
    {
        $this->assertSame(
            '[1,"a","b"]',
            Serialization::serialize(1, 'a', 'b')
        );
    }

    public function testSerializeWithNonIntegerVersion()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Version must be a positive integer.'
        );

        Serialization::serialize('1', 'a', 'b');
    }

    public function testSerializeWithInvalidIntegerVersion()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Version must be a positive integer.'
        );

        Serialization::serialize(0, 'a', 'b');
    }

    public function testUnserialize()
    {
        $handler = Phony::stub();

        Serialization::unserialize(
            '[1,"a","b"]',
            $handler
        );

        $handler->calledWith('a', 'b');
    }

    public function testUnserializeSelectsCorrectVersion()
    {
        $handlerVersion1 = Phony::stub();
        $handlerVersion2 = Phony::stub();

        Serialization::unserialize(
            '[2,"a","b"]',
            $handlerVersion1,
            $handlerVersion2
        );

        $handlerVersion1->never()->called();
        $handlerVersion2->calledWith('a', 'b');
    }

    public function testUnserializeFailureWithUnsupportedVersion()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Could not unserialization data format version 2, maximum supported version is 1.'
        );

        Serialization::unserialize(
            '[2,"a","b"]',
            Phony::stub()
        );
    }

    public function testUnserializeFailureWithNonArray()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Serialized buffers must be a JSON array with at least one element.'
        );

        Serialization::unserialize(
            '{}'
        );
    }

    public function testUnserializeFailureWithNoVersion()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Serialized buffers must be a JSON array with at least one element.'
        );

        Serialization::unserialize(
            '[]'
        );
    }
}
