<?php

namespace Icecave\Siphon\Score;

/**
 * Score serializer.
 */
interface SerializerInterface
{
    /**
     * Serialize a score to a string.
     *
     * @param Score $score
     *
     * @return string
     */
    public function serialize(Score $score);

    /**
     * Deserialize a score from a string.
     *
     * @param string $buffer
     *
     * @return Score
     * @throws InvalidArgumentException if the serialization data is malformed.
     */
    public function unserialize($buffer);
}
