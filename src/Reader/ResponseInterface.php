<?php
namespace Icecave\Siphon\Reader;

/**
 * Interface for responses.
 */
interface ResponseInterface
{
    /**
     * Dispatch a call to the given visitor.
     *
     * @param ResponseVisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(ResponseVisitorInterface $visitor);
}
