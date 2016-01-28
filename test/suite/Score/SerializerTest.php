<?php

namespace Icecave\Siphon\Score;

use PHPUnit_Framework_TestCase;

class SerializerTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->serializer = new Serializer();
    }

    /**
     * @dataProvider serializationTestVectors
     */
    public function testSerialize(Score $score)
    {
        $this->assertEquals(
            $score,
            $this->serializer->unserialize(
                $this->serializer->serialize($score)
            )
        );
    }

    public function testUnserializeVersion1()
    {
        $this->assertEquals(
            new Score(),
            $this->serializer->unserialize('[1,[]]')
        );

        $this->assertEquals(
            new Score(
                30,
                50,
                [
                    new Period(
                        PeriodType::QUARTER(),
                        1,
                        10,
                        20
                    ),
                    new Period(
                        PeriodType::QUARTER(),
                        2,
                        20,
                        30
                    ),
                ]
            ),
            $this->serializer->unserialize(
                '[1,["Q",10,20,20,30]]'
            )
        );
    }

    public function testUnserializeWithInvalidDataArray()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Invalid score format: Period data must be an array.'
        );

        $this->serializer->unserialize(
            '[1,null]'
        );
    }

    public function testUnserializeWithInvalidDataArrayPacking()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Invalid score format: No period type provided.'
        );

        $this->serializer->unserialize(
            '[1,[1,2]]'
        );
    }

    public function serializationTestVectors()
    {
        return [
            'empty' => [
                new Score(),
            ],
            'single period' => [
                new Score(
                    100,
                    200,
                    [
                        new Period(
                            PeriodType::QUARTER(),
                            1,
                            10,
                            20
                        ),
                    ]
                ),
            ],
            'multiple periods' => [
                new Score(
                    100,
                    200,
                    [
                        new Period(
                            PeriodType::QUARTER(),
                            1,
                            10,
                            20
                        ),
                        new Period(
                            PeriodType::QUARTER(),
                            2,
                            20,
                            30
                        ),
                        new Period(
                            PeriodType::OVERTIME(),
                            1,
                            0,
                            0
                        ),
                    ]
                ),
            ],
        ];
    }
}
