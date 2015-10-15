<?php

namespace Icecave\Siphon\Statistics;

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
    public function testSerialize(StatisticsCollection $collection)
    {
        $this->assertEquals(
            $collection,
            $this->serializer->unserialize(
                $this->serializer->serialize($collection)
            )
        );
    }

    public function testNumericStringsAreSerializedAsNumbers()
    {
        $this->assertEquals(
            '[1,["group","s",3],[1,0,1,2]]',
            $this->serializer->serialize(
                new StatisticsCollection(
                    [
                        new StatisticsGroup(
                            'group',
                            ['s' => '3']
                        ),
                    ]
                )
            )
        );
    }

    public function testUnserializeInvalidStringTable()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Invalid statistics format: String table must be an array.'
        );

        $this->serializer->unserialize(
            '[1,null,null]'
        );
    }

    public function testUnserializeWithInvalidDataArray()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Invalid statistics format: Group data must be an array with an even number of elements.'
        );

        $this->serializer->unserialize(
            '[1,[],null]'
        );
    }

    public function testUnserializeWithOddDataArray()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Invalid statistics format: Group data must be an array with an even number of elements.'
        );

        $this->serializer->unserialize(
            '[1,[],[1,2,3]]'
        );
    }

    public function serializationTestVectors()
    {
        return [
            'empty' => [
                new StatisticsCollection(),
            ],
            'empty group' => [
                new StatisticsCollection(
                    [new StatisticsGroup('group')]
                ),
            ],
            'single group' => [
                new StatisticsCollection(
                    [
                        new StatisticsGroup(
                            'group',
                            [
                                'sc1' => 'v1',
                                'sc2' => 'v2',
                            ],
                            [
                                'st1' => 100,
                                'st2' => 200,
                            ]
                        ),
                    ]
                ),
            ],
            'multiple groups' => [
                new StatisticsCollection(
                    [
                        new StatisticsGroup(
                            'g1',
                            [
                                'sc1' => 'v1',
                                'sc2' => 'v2',
                            ],
                            [
                                'st1' => 100,
                                'st2' => 200,
                            ]
                        ),
                        new StatisticsGroup(
                            'g2',
                            [
                                'sc1' => 'v1',
                                'sc2' => 'v2',
                            ],
                            [
                                'st2' => 250,
                                'st3' => 350,
                            ]
                        ),
                    ]
                ),
            ],
            'numeric scope value (regression)' => [
                new StatisticsCollection(
                    [
                        new StatisticsGroup(
                            'group',
                            ['s' => '3']
                        ),
                    ]
                ),
            ],
        ];
    }
}
