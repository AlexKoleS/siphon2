<?php
namespace Icecave\Siphon;

use SimpleXMLElement;

/**
 * Reads XML from a feed.
 */
interface XmlReaderInterface
{
    /**
     * Fetch XML data from a feed.
     *
     * @param string               $resource   The path to the feed.
     * @param array<string, mixed> $parameters Additional parameters to pass.
     *
     * @return SimpleXMLElement The XML response.
     */
    public function read($resource, array $parameters = []);
}
