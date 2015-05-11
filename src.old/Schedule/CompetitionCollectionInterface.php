<?php
namespace Icecave\Siphon\Schedule;

use Countable;
use IteratorAggregate;

/**
 * A collection of competitions.
 *
 * @api
 */
interface CompetitionCollectionInterface extends Countable, IteratorAggregate
{
    /**
     * Get the competitions in the collection.
     *
     * @return array<CompetitionInterface>
     */
    public function competitions();

    /**
     * Indicates whether or not the schedule contains any competitions.
     *
     * @return boolean True if the collection is empty; otherwise, false.
     */
    public function isEmpty();
}
