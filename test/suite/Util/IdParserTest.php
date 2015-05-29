<?php
namespace Icecave\Siphon\Util;

use Icecave\Siphon\Sport;
use PHPUnit_Framework_TestCase;

class IdParserTest extends PHPUnit_Framework_TestCase
{
    public function testParseInt()
    {
        $this->assertSame(
            123,
            IdParser::parse(123, Sport::NFL(), '<type>')
        );
    }

    public function testParseNumericString()
    {
        $this->assertSame(
            123,
            IdParser::parse('123', Sport::NFL(), '<type>')
        );
    }

    public function testParseString()
    {
        $this->assertSame(
            123,
            IdParser::parse('/sport/football/<type>:123', Sport::NFL(), '<type>')
        );
    }

    public function testParseStringWithInvalidType()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Invalid ID: "/sport/football/<different-type>:123", expected NFL <type> ID'
        );

        IdParser::parse('/sport/football/<different-type>:123', Sport::NFL(), '<type>');
    }

    public function testParseStringWithInvalidSport()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Invalid ID: "/sport/basketball/<type>:123", expected NFL <type> ID'
        );

        IdParser::parse('/sport/basketball/<type>:123', Sport::NFL(), '<type>');
    }
}
