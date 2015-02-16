<?php
namespace Icecave\Siphon;

use InvalidArgumentException;

/**
 * Build a feed URL.
 */
class UrlBuilder implements UrlBuilderInterface
{
    /**
     * @param string $apiKey The secret API key used to authenticate.
     * @param string $base   The base URL.
     */
    public function __construct(
        $apiKey,
        $base = 'http://xml.sportsdirectinc.com'
    ) {
        $this->apiKey = $apiKey;
        $this->base   = $base;
    }

    /**
     * Build a feed URL.
     *
     * @param string               $resource   The path to the feed.
     * @param array<string, mixed> $parameters Additional parameters to pass.
     *
     * @return string The full URL.
     */
    public function build($resource, array $parameters = [])
    {
        if ($resource[0] !== '/') {
            throw new InvalidArgumentException(
                'Resource must begin with a slash.'
            );
        }

        $url = sprintf(
            '%s%s?apiKey=%s',
            $this->base,
            $resource,
            urlencode($this->apiKey)
        );

        foreach ($parameters as $key => $value) {
            $url .= '&' . urlencode($key) . '=' . urlencode($value);
        }

        return $url;
    }

    private $apiKey;
    private $base;
}
