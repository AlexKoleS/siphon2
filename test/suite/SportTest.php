<?php
namespace Icecave\Siphon;

use PHPUnit_Framework_TestCase;

class SportTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->sports = [
            'NFL'   => 'football',
            'NCAAF' => 'football',
            'NBA'   => 'basketball',
            'NCAAB' => 'basketball',
            'MLB'   => 'baseball',
            'NHL'   => 'hockey',
        ];
    }

    public function testFromComponents()
    {
        foreach ($this->sports as $league => $sport) {
            $this->assertSame(
                Sport::memberByKey($league),
                Sport::fromComponents($sport, $league)
            );
        }
    }

    public function testSportAndLeague()
    {
        foreach (Sport::members() as $sport) {
            $this->assertSame(
                $sport->sport(),
                $this->sports[$sport->league()]
            );

            unset($this->sports[$sport->league()]);
        }

        $this->assertSame(
            [],
            $this->sports,
            'The following league -> sport mappings have not been tested:'
        );
    }
}
