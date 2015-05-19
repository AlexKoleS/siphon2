<?php
namespace Icecave\Siphon\Reader\Exception;

use Exception;
use RuntimeException;

/**
 * Indicates that the feed service is currently unavailable.
 */
class NotFoundException extends RuntimeException implements SiphonExceptionInterface
{
    public function __construct(Exception $previous = null)
    {
        parent::__construct('Feed not found.', 0, $previous);
    }
}
