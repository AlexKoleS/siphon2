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
        $data = $score->score();

        foreach ($score as $period) {
            if ($code !== $period->type()->code()) {
                $code   = $period->type()->code();
                $data[] = $code;
            }

            $data[] = $period->homeScore();
            $data[] = $period->awayScore();
        }

        return Serialization::serialize(
            2, // version 1
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
                return $this->unserializeVersion1($data);
            },
            function ($data) {
                return $this->unserializeVersion2($data);
            }
        );
    }

    private function unserializePeriods(array $data, $index)
    {
        $type    = null;
        $number  = 1;
        $periods = [];
        $length  = count($data);

        while ($index < $length) {
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

        return $periods;
    }

    private function unserializeVersion1($data)
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException(
                'Invalid score format: Period data must be an array.'
            );
        }

        $periods = $this->unserializePeriods($data, 0);
        $homeTotal = 0;
        $awayTotal = 0;

        foreach ($periods as $period) {
            $homeTotal += $period->homeScore();
            $awayTotal += $period->awayScore();
        }

        return new Score(
            $homeTotal,
            $awayTotal,
            $periods
        );
    }

    private function unserializeVersion2($data)
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException(
                'Invalid score format: Data must be an array.'
            );
        } elseif (count($data) < 2) {
            throw new InvalidArgumentException(
                'Invalid score format: Data must contain at least two elements.'
            );
        }

        return new Score(
            $data[0],
            $data[1],
            $this->unserializePeriods($data, 2)
        );
    }
}
