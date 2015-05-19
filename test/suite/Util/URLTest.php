<?php
namespace Icecave\Siphon\Util;

use PHPUnit_Framework_TestCase;

class URLTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider stripParameterTestVectors
     */
    public function testStripParameter($input, $output)
    {
        $this->assertSame(
            $output,
            URL::stripParameter($input, 'key')
        );
    }

    public function stripParameterTestVectors()
    {
        return [
            'not present'                 => ['http://domain/path/?other=true',                   'http://domain/path/?other=true'],
            'only parameter'              => ['http://domain/path/?key=value',                    'http://domain/path/'],
            'only parameter, no value'    => ['http://domain/path/?key=',                         'http://domain/path/'],
            'first parameter'             => ['http://domain/path/?key=value&post=true',          'http://domain/path/?post=true'],
            'first parameter, no value'   => ['http://domain/path/?key=&post=true',               'http://domain/path/?post=true'],
            'last parameter'              => ['http://domain/path/?pre=true&key=value',           'http://domain/path/?pre=true'],
            'last parameter, no value'    => ['http://domain/path/?pre=true&key=',                'http://domain/path/?pre=true'],
            'middle parameter'            => ['http://domain/path/?pre=true&key=value&post=true', 'http://domain/path/?pre=true&post=true'],
            'middle parameter, no value'  => ['http://domain/path/?pre=true&key=&post=true',      'http://domain/path/?pre=true&post=true'],
        ];
    }
}
