<?php
namespace Icecave\Siphon\Reader\Exception;

use Exception;
use RuntimeException;

/**
 * Indicates that the feed service is currently unavailable.
 */
class ServiceUnavailableException extends RuntimeException implements SiphonExceptionInterface
{
    public function __construct(Exception $previous = null)
    {
        parent::__construct('Service unavailable.', 0, $previous);
    }
}
