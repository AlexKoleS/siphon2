<?php
namespace Icecave\Siphon;

use PHPUnit_Framework_TestCase;

class UtilTest extends PHPUnit_Framework_TestCase
{
    public function testExtractNumericId()
    {
        $this->assertSame(
            123,
            Util::extractNumericId('/foo/bar:123')
        );
    }

    public function testExtractNumericIdFailure()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Invalid identifier.'
        );

        Util::extractNumericId('/foo/bar.123');
    }
}
