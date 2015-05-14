<?php
namespace Icecave\Siphon\Util;

use PHPUnit_Framework_TestCase;

class XPathTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->xml = simplexml_load_string(
            '<foo><str>text</str><int>23</int><float>2.3</float></foo>'
        );
    }

    public function testElement()
    {
        $this->assertEquals(
            $this->xml->xpath('//str')[0],
            XPath::element($this->xml, '//str')
        );
    }

    public function testElementFailure()
    {
        $this->setExpectedException(
            'RuntimeException',
            'Path not found: "//not-found".'
        );

        XPath::element($this->xml, '//not-found');
    }

    public function testElementOrNull()
    {
        $this->assertEquals(
            $this->xml->xpath('//str')[0],
            XPath::elementOrNull($this->xml, '//str')
        );
    }

    public function testElementOrNullFailure()
    {
        $this->assertNull(
            XPath::elementOrNull($this->xml, '//not-found')
        );
    }

    public function testString()
    {
        $this->assertSame(
            strval($this->xml->xpath('//str')[0]),
            XPath::string($this->xml, '//str')
        );
    }

    public function testStringFailure()
    {
        $this->setExpectedException(
            'RuntimeException',
            'Path not found: "//not-found".'
        );

        XPath::string($this->xml, '//not-found');
    }

    public function testStringOrNull()
    {
        $this->assertSame(
            strval($this->xml->xpath('//str')[0]),
            XPath::stringOrNull($this->xml, '//str')
        );
    }

    public function testStringOrNullFailure()
    {
        $this->assertNull(
            XPath::stringOrNull($this->xml, '//not-found')
        );
    }

    public function testNumber()
    {
        $this->assertSame(
            23,
            XPath::number($this->xml, '//int')
        );

        $this->assertSame(
            2.3,
            XPath::number($this->xml, '//float')
        );
    }

    public function testNumberFailure()
    {
        $this->setExpectedException(
            'RuntimeException',
            'Path not found: "//not-found".'
        );

        XPath::number($this->xml, '//not-found');
    }

    public function testNumberFailureWithNonNumeric()
    {
        $this->setExpectedException(
            'RuntimeException',
            'Path does not resolve to a number: "//str".'
        );

        XPath::number($this->xml, '//str');
    }

    public function testNumberOrNull()
    {
        $this->assertEquals(
            23,
            XPath::numberOrNull($this->xml, '//int')
        );
    }

    public function testNumberOrNullFailure()
    {
        $this->assertNull(
            XPath::numberOrNull($this->xml, '//not-found')
        );
    }
}
