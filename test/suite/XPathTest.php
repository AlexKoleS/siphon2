<?php
namespace Icecave\Siphon;

use PHPUnit_Framework_TestCase;

class XPathTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->xml = simplexml_load_string(
            '<foo><bar>text</bar></foo>'
        );
    }

    public function testElement()
    {
        $this->assertEquals(
            $this->xml->xpath('//bar')[0],
            XPath::element($this->xml, '//bar')
        );
    }

    public function testElementFailure()
    {
        $this->setExpectedException(
            'RuntimeException',
            'XPath not found: "//not-found".'
        );

        XPath::element($this->xml, '//not-found');
    }

    public function testElementOrNull()
    {
        $this->assertEquals(
            $this->xml->xpath('//bar')[0],
            XPath::elementOrNull($this->xml, '//bar')
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
        $this->assertEquals(
            strval($this->xml->xpath('//bar')[0]),
            XPath::string($this->xml, '//bar')
        );
    }

    public function testStringFailure()
    {
        $this->setExpectedException(
            'RuntimeException',
            'XPath not found: "//not-found".'
        );

        XPath::string($this->xml, '//not-found');
    }

    public function testStringOrNull()
    {
        $this->assertEquals(
            strval($this->xml->xpath('//bar')[0]),
            XPath::stringOrNull($this->xml, '//bar')
        );
    }

    public function testStringOrNullFailure()
    {
        $this->assertNull(
            XPath::stringOrNull($this->xml, '//not-found')
        );
    }
}
