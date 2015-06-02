<?php
namespace Icecave\Siphon\Statistics;

use PHPUnit_Framework_TestCase;

class SerializerTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->serializer = new Serializer;
    }

    /**
     * @dataProvider serializationTestVectors
     */
    public function testSerialize($buffer, StatisticsCollection $collection)
    {
        $this->assertSame(
            $buffer,
            $this->serializer->serialize($collection)
        );
    }

    /**
     * @dataProvider serializationTestVectors
     */
    public function testUnserialize($buffer, StatisticsCollection $collection)
    {
        $this->assertEquals(
            $collection,
            $this->serializer->unserialize($buffer)
        );
    }

    public function testUnserializeWithMalformedGroup()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Invalid statistics format: Groups must be a 2-tuple.'
        );

        $this->serializer->unserialize(
            '[1,["group"],[[]]]'
        );
    }

    public function testUnserializeWithMalformedGroupScope()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Invalid statistics format: Group scopes must be an object.'
        );

        $this->serializer->unserialize(
            '[1,["group"],[[null,null]]]'
        );
    }

    public function testUnserializeWithMalformedGroupStatistics()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Invalid statistics format: Group statistics must be an object.'
        );

        $this->serializer->unserialize(
            '[1,["group"],[[{},null]]]'
        );
    }

    public function serializationTestVectors()
    {
        return [
            'empty' => [
                '[1,[],[]]',
                new StatisticsCollection,
            ],
            'empty group' => [
                '[1,["group"],[[{},{}]]]',
                new StatisticsCollection(
                    [new StatisticsGroup('group')]
                ),
            ],
            'single group' => [
                '[1,["group","sc1","v1","sc2","v2","st1","st2"],[[{"1":2,"3":4},{"5":100,"6":200}]]]',
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
                '[1,["g1","g2","sc1","v1","sc2","v2","st1","st2","st3"],[[{"2":3,"4":5},{"6":100,"7":200}],[{"2":3,"4":5},{"7":250,"8":350}]]]',
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
        ];
    }
}
