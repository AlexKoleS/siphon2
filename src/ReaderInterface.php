<?php
namespace Icecave\Siphon;

use Icecave\Siphon\Atom\AtomEntry;
use InvalidArgumentException;

/**
 * Base interface for readers.
 */
interface ReaderInterface
{
    /**
     * Read a feed based on an atom entry.
     *
     * @param AtomEntry $atomEntry
     *
     * @return mixed
     * @throws InvalidArgumentException if this atom entry is not supported.
     */
    public function readAtomEntry(AtomEntry $atomEntry);

    /**
     * Check if the given atom entry can be used by this reader.
     *
     * @param AtomEntry $atomEntry
     *
     * @return boolean
     */
    public function supportsAtomEntry(AtomEntry $atomEntry);
}
