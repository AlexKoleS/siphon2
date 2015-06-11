<?php
namespace Icecave\Siphon\Score;

use Eloquent\Enumeration\Exception\UndefinedMemberException;
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
            foreach (PeriodType::members() as $type) {
                $used = in_array(
                    $type,
                    $this->sports[$sport->key()],
                    true
                );

                if ($used) {
                    $this->assertSame(
                        $type,
                        PeriodType::memberBySportAndValue($sport, $type->value())
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

    public function testMemberByCode()
    {
        foreach (PeriodType::members() as $type) {
            $this->assertSame(
                $type,
                PeriodType::memberByCode($type->code())
            );
        }
    }

    public function testMemberByCodeFailure()
    {
        $this->setExpectedException(
            UndefinedMemberException::class,
            "No member with code equal to '?'"
        );

        PeriodType::memberByCode('?');
    }

    public function testCode()
    {
        $this->assertSame('Q', PeriodType::QUARTER()->code());
        $this->assertSame('H', PeriodType::HALF()->code());
        $this->assertSame('P', PeriodType::PERIOD()->code());
        $this->assertSame('O', PeriodType::OVERTIME()->code());
        $this->assertSame('S', PeriodType::SHOOTOUT()->code());
        $this->assertSame('I', PeriodType::INNING()->code());
        $this->assertSame('E', PeriodType::EXTRA_INNING()->code());
    }

    public function testCodesAreUnique()
    {
        $codes = [];

        foreach (PeriodType::members() as $type) {
            $this->assertNotContains(
                $type->code(),
                $codes,
                $type . ' shares code "' . $type->code() . '" with another type.'
            );

            $codes[] = $type->code();
        }
    }

    public function testUsedBy()
    {
        foreach (Sport::members() as $sport) {
            foreach (PeriodType::members() as $type) {
                $this->assertSame(
                    in_array(
                        $type,
                        $this->sports[$sport->key()],
                        true
                    ),
                    $type->usedBy($sport)
                );
            }
        }
    }
}
