<?php

namespace Icecave\Siphon\Statistics;

use Icecave\Siphon\Util\Serialization;
use InvalidArgumentException;

/**
 * Statistics serializer.
 *
 * This implementation serializes a StatisticsCollection into JSON
 * format using a string table to avoid needless duplication of key
 * names as is common with statistics.
 *
 * The string table maps integer indices to the string value. The
 * indices are used throughout the remainder of the result instead of
 * the strings.
 *
 * The final result is a JSON array with three elements. The first element is
 * the serialization version. The second element is the string table encoded as
 * an array. The third element is a flattened data array of encoded StatisticGroup
 * objects.
 *
 * The data array contains the following elements for each group, in order:
 *
 * - The number of scope entries in the group.
 * - The number of statistics entries in the group.
 * - The string table index for the key of the first scope
 * - The string table index for the value of the first scope
 * - .. (key / value indices repeated for each of the scopes) ...
 * - The string table index for the key of the first statistic
 * - The value of the first statistic
 * - ... (key index / values repeated for each of the statistics) ...
 */
class Serializer implements SerializerInterface
{
    /**
     * Serialize a statistics collection to a string.
     *
     * @param StatisticsCollection $collection
     *
     * @return string
     */
    public function serialize(StatisticsCollection $collection)
    {
        $table  = [];
        $data   = [];
        $store  = function ($string) use (&$table) {
            return array_key_exists($string, $table)
                ? ($table[$string])
                : ($table[$string] = count($table));
        };

        // Pre-store the group keys in the string table so their index
        // corresponds to the order in the result ...
        foreach ($collection as $key => $group) {
            $store($key);
        }

        // Encode the groups ...
        foreach ($collection as $group) {
            $data[] = count($group->scopes());
            $data[] = count($group->statistics());

            foreach ($group->scopes() as $k => $v) {
                $data[] = $store($k);
                $data[] = $store($v);
            }

            foreach ($group->statistics() as $k => $v) {
                $data[] = $store($k);
                $data[] = $v;
            }
        }

        return Serialization::serialize(
            1, // version 1
            array_flip($table),
            $data
        );
    }

    /**
     * Deserialize a statistics collection from a string.
     *
     * @param string $buffer
     *
     * @return StatisticsCollection
     * @throws InvalidArgumentException if the serialization data is malformed.
     */
    public function unserialize($buffer)
    {
        return Serialization::unserialize(
            $buffer,
            function ($table, $data) {
                return $this->unserializeVersion1($table, $data);
            }
        );
    }

    /**
     * Unserialize a statistics collection encoded in version 1.
     *
     * @param array<integer,string> $table The string table.
     * @param array<integer|float>  $data  The encoded groups.
     *
     * @return StatisticsCollection
     * @throws InvalidArgumentException if the serialization data is malformed.
     */
    private function unserializeVersion1($table, $data)
    {
        if (!is_array($table)) {
            throw new InvalidArgumentException(
                'Invalid statistics format: String table must be an array.'
            );
        } elseif (!is_array($data) || count($data) % 2) {
            throw new InvalidArgumentException(
                'Invalid statistics format: Group data must be an array with an even number of elements.'
            );
        }

        $groups = [];
        $index  = 0;

        while ($index < count($data)) {
            $numScopes = $data[$index++];
            $numStats  = $data[$index++];

            $scopes = [];
            while ($numScopes--) {
                $key          = $table[$data[$index++]];
                $value        = $table[$data[$index++]];

                // numeric strings are serialized as numbers to save space, but
                // the group expects only strings
                $scopes[$key] = strval($value);
            }

            $stats = [];
            while ($numStats--) {
                $key         = $table[$data[$index++]];
                $value       = $data[$index++];
                $stats[$key] = $value;
            }

            $groups[] = new StatisticsGroup(
                $table[count($groups)],
                $scopes,
                $stats
            );
        }

        return new StatisticsCollection($groups);
    }
}
