<?php

namespace Icecave\Siphon\Reader;

use SimpleXMLElement;

/**
 * Reads XML data.
 */
interface XmlReaderInterface
{
    /**
     * Read XML data from a URL.
     *
     * @param string $url The URL.
     *
     * @return tuple<SimpleXMLElement, DateTime|null> [via promise] A 2-tuple of the XML responser and the last modification time, if available.
     */
    public function read($url);
}
