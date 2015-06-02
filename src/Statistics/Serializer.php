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
 * an array. The third element is an array of encoded StatisticGroup objects.
 *
 * Each group is encoded a 2-tuple of scopes and statistics. The group's
 * position in the result is used to determine the string table index for the
 * group's key.  For example the key of the first group in a result is given at
 * $table[0].
 *
 * Within a group, both the key and value of scopes are stored in the string
 * table. Statistics values are already numeric and hence only the key is stored
 * in the string table.
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
        $groups = [];
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
            $scopes = [];
            $stats  = [];

            foreach ($group->scopes() as $k => $v) {
                $scopes[$store($k)] = $store($v);
            }

            foreach ($group->statistics() as $k => $v) {
                $stats[$store($k)] = $v;
            }

            $groups[] = [
                (object) $scopes,
                (object) $stats,
            ];
        }

        return Serialization::serialize(
            1, // version 1
            array_flip($table),
            $groups
        );
    }

    /**
     * Dersialize a statistics collection from a string.
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
            function ($table, $groups) {
                return $this->unserializeVersion1($table, $groups);
            }
        );
    }

    /**
     * Unserialize a statistics collection encoded in version 1.
     *
     * @param array<integer,string>       $table  The string table.
     * @param array<tuple<object,object>> $groups The encoded groups.
     *
     * @return StatisticsCollection
     * @throws InvalidArgumentException if the serialization data is malformed.
     */
    private function unserializeVersion1(array $table, array $groups)
    {
        $decodedGroups = [];

        foreach ($groups as $groupkeyIndex => $group) {
            if (!is_array($group) || 2 !== count($group)) {
                throw new InvalidArgumentException(
                    'Invalid statistics format: Groups must be a 2-tuple.'
                );
            }

            list($scopes, $stats) = $group;

            if (!is_object($scopes)) {
                throw new InvalidArgumentException(
                    'Invalid statistics format: Group scopes must be an object.'
                );
            } elseif (!is_object($stats)) {
                throw new InvalidArgumentException(
                    'Invalid statistics format: Group statistics must be an object.'
                );
            }

            $decodedScopes = [];
            $decodedStats  = [];

            foreach ($scopes as $keyIndex => $valueIndex) {
                $decodedScopes[$table[$keyIndex]] = $table[$valueIndex];
            }

            foreach ($stats as $keyIndex => $value) {
                $decodedStats[$table[$keyIndex]] = $value;
            }

            $decodedGroups[] = new StatisticsGroup(
                $table[$groupkeyIndex],
                $decodedScopes,
                $decodedStats
            );
        }

        return new StatisticsCollection($decodedGroups);
    }
}
