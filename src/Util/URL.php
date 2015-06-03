<?php
namespace Icecave\Siphon\Util;

/**
 * Internal utilities for URL operations.
 */
abstract class URL
{
    public static function stripParameter($url, $name)
    {
        return preg_replace_callback(
            '/([?&])' . preg_quote($name) . '=[^&]*(&)?/',
            function ($matches) {
                return isset($matches[2])
                        ? $matches[1]
                        : '';
            },
            $url
        );
    }
}
