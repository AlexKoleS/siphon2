<?php
namespace Icecave\Siphon\Reader;

/**
 * Interface for requests.
 */
interface RequestInterface
{
    /**
     * Dispatch a call to the given visitor.
     *
     * @param VisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(RequestVisitorInterface $visitor);
}
