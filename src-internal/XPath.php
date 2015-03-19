<?php
namespace Icecave\Siphon;

use RuntimeException;
use SimpleXMLElement;

abstract class XPath
{
    public static function element(SimpleXMLElement $element, $xpath)
    {
        $result = static::elementOrNull($element, $xpath);

        if (null === $result) {
            throw new RuntimeException(
                'XPath not found: "' . $xpath . '".'
            );
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
        return strval(static::element($element, $xpath));
    }

    public static function stringOrNull(SimpleXMLElement $element, $xpath)
    {
        $result = static::elementOrNull($element, $xpath);

        if (null === $result) {
            return null;
        }

        return strval($result);
    }
}
