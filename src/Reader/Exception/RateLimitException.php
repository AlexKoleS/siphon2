<?php

namespace Icecave\Siphon\Reader\Exception;

use Exception;
use RuntimeException;

class RateLimitException extends RuntimeException implements SiphonExceptionInterface
{
    public function __construct(Exception $previous = null)
    {
        parent::__construct('Rate limit in effect.', 0, $previous);
    }
}
