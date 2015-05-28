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
                'Serialization data must be a JSON array containing at least one element.'
            );
        }

        $version = array_shift($data);
        $handler = func_get_arg($version); // parameter 0 is the buffer

        return call_user_func_array($handler, $data);
    }
}
