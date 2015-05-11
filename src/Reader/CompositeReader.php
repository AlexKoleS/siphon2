<?php
namespace Icecave\Siphon\Reader;

use InvalidArgumentException;

/**
 * A composite of many different feed readers.
 */
class CompositeReader implements ReaderInterface
{
    public function __construct(array $readers = [])
    {
        foreach ($readers as $reader) {
            $this->add($reader);
        }
    }

    /**
     * Make a request and return the response.
     *
     * @param RequestInterface The request.
     *
     * @return ResponseInterface        The response.
     * @throws InvalidArgumentException if the request is not supported.
     */
    public function read(RequestInterface $request)
    {
        foreach ($this->readers as $reader) {
            if ($reader->isSupported($request)) {
                return $reader->read($request);
            }
        }

        throw new InvalidArgumentException('Unsupported request.');
    }

    /**
     * Check if the given request is supported.
     *
     * @return boolean True if the given request is supported; otherwise, false.
     */
    public function isSupported(RequestInterface $request)
    {
        foreach ($this->readers as $reader) {
            if ($reader->isSupported($request)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add a reader.
     *
     * @param ReaderInterface $reader The reader to add.
     */
    public function add(ReaderInterface $reader)
    {
        $this->readers[spl_object_hash($reader)] = $reader;
    }

    /**
     * Remove a reader.
     *
     * @param ReaderInterface $reader The reader to remove.
     */
    public function remove(ReaderInterface $reader)
    {
        unset($this->readers[spl_object_hash($reader)]);
    }

    private $readers;
}
