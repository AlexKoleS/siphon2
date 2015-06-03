<?php
namespace Icecave\Siphon\Util;

use RuntimeException;
use SimpleXMLElement;

/**
 * Internal utilities for XPath operations.
 */
abstract class XPath
{
    public static function element(SimpleXMLElement $element, $xpath)
    {
        $result = static::elementOrNull($element, $xpath);

        if (null === $result) {
            throw static::createException($xpath);
        }

        return $result;
    }

    public static function elementOrNull(SimpleXMLElement $element, $xpath)
    {
        $result = $element->xpath($xpath);

        if ($result) {
            return current($result);
        }

        return null;
    }

    public static function string(SimpleXMLElement $element, $xpath)
    {
        $result = static::stringOrNull($element, $xpath);

        if (null === $result) {
            throw static::createException($xpath);
        }

        return $result;
    }

    public static function stringOrNull(SimpleXMLElement $element, $xpath)
    {
        $result = static::elementOrNull($element, $xpath);

        if (null === $result) {
            return null;
        }

        return strval($result);
    }

    public static function number(SimpleXMLElement $element, $xpath)
    {
        $result = static::numberOrNull($element, $xpath);

        if (null === $result) {
            throw static::createException($xpath);
        }

        return $result;
    }

    public static function numberOrNull(SimpleXMLElement $element, $xpath)
    {
        $result = static::stringOrNull($element, $xpath);

        if (null === $result) {
            return null;
        } elseif (!is_numeric($result)) {
            throw self::createException($xpath, 'Path does not resolve to a number');
        } elseif (ctype_digit($result)) {
            return intval($result);
        } else {
            return floatval($result);
        }
    }

    private static function createException($xpath, $message = 'Path not found')
    {
        throw new RuntimeException(
            $message . ': "' . $xpath . '".'
        );
    }
}
