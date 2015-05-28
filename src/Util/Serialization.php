<?php
namespace Icecave\Siphon\Util;

use InvalidArgumentException;

/**
 * Internal utilities for serialization.
 */
abstract class Serialization
{
    /**
     * @param integer $version
     * @param mixed   $data,...
     */
    public static function serialize($version)
    {
        if (!is_int($version) || $version < 1) {
            throw new InvalidArgumentException('Version must be a positive integer.');
        }

        return json_encode(func_get_args());
    }

    /**
     * @param string   $buffer
     * @param callable $handler,...
     */
    public static function unserialize($buffer)
    {
        $data = @json_decode($buffer);

        if (!is_array($data) || empty($data)) {
            throw new InvalidArgumentException(
                'Serialized buffers must be a JSON array with at least one element.'
            );
        }

        $version   = array_shift($data);
        $supported = func_num_args() - 1;

        if ($version > $supported) {
            throw new InvalidArgumentException(
                sprintf(
                    'Could not unserialization data format version %d, maximum supported version is %d.',
                    $version,
                    $supported
                )
            );
        }

        return call_user_func_array(
            func_get_arg($version), // parameter 0 is the buffer
            $data
        );
    }
}
