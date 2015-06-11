<?php
namespace Icecave\Siphon\Score;

use Icecave\Siphon\Util\Serialization;
use InvalidArgumentException;

/**
 * Score serializer.
 */
class Serializer implements SerializerInterface
{
    /**
     * Serialize a score to a string.
     *
     * @param Score $score
     *
     * @return string
     */
    public function serialize(Score $score)
    {
        $code = null;
        $data = [];

        foreach ($score as $period) {
            if ($code !== $period->type()->code()) {
                $code = $period->type()->code();
                $data[] = $code;
            }

            $data[] = $period->homeScore();
            $data[] = $period->awayScore();
        }

        return Serialization::serialize(
            1, // version 1
            $data
        );
    }

    /**
     * Deserialize a score from a string.
     *
     * @param string $buffer
     *
     * @return Score
     * @throws InvalidArgumentException if the serialization data is malformed.
     */
    public function unserialize($buffer)
    {
        return Serialization::unserialize(
            $buffer,
            function ($data) {
                if (!is_array($data)) {
                    throw new InvalidArgumentException(
                        'Invalid score format: Period data must be an array.'
                    );
                }

                $index   = 0;
                $type    = null;
                $number  = 1;
                $periods = [];

                while ($index < count($data)) {
                    if (is_string($data[$index])) {
                        $type   = PeriodType::memberByCode($data[$index++]);
                        $number = 1;
                    } elseif (null === $type) {
                        throw new InvalidArgumentException(
                            'Invalid score format: No period type provided.'
                        );
                    }

                    $periods[] = new Period(
                        $type,
                        $number++,
                        $data[$index++],
                        $data[$index++]
                    );
                }

                return new Score($periods);
            }
        );
    }
}
