<?php
namespace Icecave\Siphon\Reader;

use Serializable;

/**
 * Interface for requests.
 */
interface RequestInterface extends Serializable
{
    /**
     * Dispatch a call to the given visitor.
     *
     * @param RequestVisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(RequestVisitorInterface $visitor);
}
