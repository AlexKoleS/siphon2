<?php

namespace Icecave\Siphon;

use Icecave\Siphon\Reader\ReaderInterface;
use Icecave\Siphon\Reader\UrlBuilderInterface;
use Icecave\Siphon\Reader\XmlReaderInterface;

/**
 * The dispatcher is a facade for easily servicing any Siphon request.
 */
interface DispatcherInterface extends ReaderInterface
{
    /**
     * Get the URL builder used by the factory.
     *
     * @return UrlBuilderInterface
     */
    public function urlBuilder();

    /**
     * Get the XML reader used by the factory.
     *
     * @return XmlReaderInterface
     */
    public function xmlReader();
}
