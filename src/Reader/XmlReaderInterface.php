<?php

namespace Icecave\Siphon\Reader;

use SimpleXMLElement;

/**
 * Read XML data based on a resource name.
 */
interface XmlReaderInterface
{
    /**
     * Read XML data from a feed.
     *
     * @param string               $resource   The path to the feed.
     * @param array<string, mixed> $parameters Additional parameters to pass.
     *
     * @return SimpleXMLElement [via promise] The XML response.
     */
    public function read($resource, array $parameters = []);
}
