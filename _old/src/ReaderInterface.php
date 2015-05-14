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
     * @param AtomEntry $atomEntry The atom entry.
     *
     * @return mixed
     * @throws InvalidArgumentException if this atom entry is not supported.
     */
    public function readAtomEntry(AtomEntry $atomEntry);

    /**
     * Check if the given atom entry can be used by this reader.
     *
     * @param AtomEntry $atomEntry   The atom entry.
     * @param array     &$parameters Populated with reader-specific parameters represented by the atom entry.
     *
     * @return boolean
     */
    public function supportsAtomEntry(
        AtomEntry $atomEntry,
        array &$parameters = []
    );
}
