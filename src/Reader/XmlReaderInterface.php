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
     * @return SimpleXMLElement [via promise] The XML response.
     */
    public function read($url);
}
