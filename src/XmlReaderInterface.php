<?php
namespace Icecave\Sid;

use SimpleXMLElement;

/**
 * Fetch XML data from a feed.
 */
interface XmlReaderInterface
{
    /**
     * Read an XML feed.
     *
     * @param string               $resource   The path to the feed.
     * @param array<string, mixed> $parameters Additional parameters to pass.
     *
     * @return SimpleXMLElement The XML response.
     */
    public function read($resource, array $parameters = []);
}
