<?php

namespace Icecave\Siphon\Statistics;

/**
 * Statistics serializer.
 */
interface SerializerInterface
{
    /**
     * Serialize a statistics collection to a string.
     *
     * @param StatisticsCollection $statistics
     *
     * @return string
     */
    public function serialize(StatisticsCollection $statistics);

    /**
     * Deserialize a statistics collection from a string.
     *
     * @param string $buffer
     *
     * @return StatisticsCollection
     * @throws InvalidArgumentException if the serialization data is malformed.
     */
    public function unserialize($buffer);
}
