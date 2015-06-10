<?php
namespace Icecave\Siphon\Score;

use Icecave\Siphon\Sport;
use PHPUnit_Framework_TestCase;

class PeriodTypeTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->sports = [
            'NFL'   => [PeriodType::QUARTER(), PeriodType::OVERTIME()],
            'NCAAF' => [PeriodType::QUARTER(), PeriodType::OVERTIME()],
            'NBA'   => [PeriodType::QUARTER(), PeriodType::OVERTIME()],
            'NCAAB' => [PeriodType::HALF(),    PeriodType::OVERTIME()],
            'NHL'   => [PeriodType::PERIOD(),  PeriodType::OVERTIME(), PeriodType::SHOOTOUT()],
            'MLB'   => [PeriodType::INNING(),  PeriodType::EXTRA_INNING()],
        ];
    }

    public function testMemberBySportAndValue()
    {
        foreach (Sport::members() as $sport) {
            foreach (PeriodType::members() as $periodType) {
                $used = in_array(
                    $periodType,
                    $this->sports[$sport->key()],
                    true
                );

                if ($used) {
                    $this->assertSame(
                        $periodType,
                        PeriodType::memberBySportAndValue($sport, $periodType->value())
                    );
                }
            }
        }
    }

    public function testMemberBySportAndValueFailure()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'NHL does not use the INNING period type.'
        );

        PeriodType::memberBySportAndValue(
            Sport::NHL(),
            'inning'
        );
    }

    public function testUsedBy()
    {
        foreach (Sport::members() as $sport) {
            foreach (PeriodType::members() as $periodType) {
                $this->assertSame(
                    in_array(
                        $periodType,
                        $this->sports[$sport->key()],
                        true
                    ),
                    $periodType->usedBy($sport)
                );
            }
        }
    }
}
