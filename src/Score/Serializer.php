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
        $data = [];

        foreach ($score as $period) {
            $data[] = $period->type()->code();
            $data[] = $period->number();
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
                if (!is_array($data) || count($data) % 4) {
                    throw new InvalidArgumentException(
                        'Invalid score format: Period data must be an array with element count that is a multiple of four.'
                    );
                }

                $index   = 0;
                $periods = [];

                while ($index < count($data)) {
                    $type   = PeriodType::memberByCode($data[$index++]);
                    $number = $data[$index++];
                    $home   = $data[$index++];
                    $away   = $data[$index++];

                    $periods[] = new Period(
                        $type,
                        $number,
                        $home,
                        $away
                    );
                }

                return new Score($periods);
            }
        );
    }
}
