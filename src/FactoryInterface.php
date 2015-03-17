<?php
namespace Icecave\Siphon;

/**
 * The global factory used to create Siphon feed readers.
 *
 * @api
 */
interface FactoryInterface
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

    /**
     * Create an atom reader.
     *
     * @return Atom\AtomReaderInterface
     */
    public function createAtomReader();

    /**
     * Create a schedule reader.
     *
     * @return Schedule\ScheduleReaderInterface
     */
    public function createScheduleReader();
}
