<?php
namespace Icecave\Siphon;

use InvalidArgumentException;

/**
 * Constructs a fully-qualified feed URL from a resource name and parameter set.
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
        $this->base   = rtrim($base, '/');
    }

    /**
     * Build a feed URL.
     *
     * @param string               $resource   The path to the feed.
     * @param array<string, mixed> $parameters Additional parameters to pass.
     *
     * @return string The fully-qualified feed URL.
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

    /**
     * Extract the resource and parameters from a fully-qualified URL.
     *
     * @param string $url The URL.
     *
     * @return tuple<string, array> A 2-tuple containing the resource and parameters.
     */
    public function extract($url)
    {
        if (0 !== strpos($url, $this->base)) {
            throw new InvalidArgumentException(
                'The given URL does not match the base URL.'
            );
        }

        $components = parse_url($url);
        $parameters = [];

        if (isset($components['path'])) {
            $path = $components['path'];
        } else {
            $path = '/';
        }

        if (isset($components['query'])) {
            parse_str($components['query'], $parameters);
            unset($parameters['apiKey']);
        }

        return [$path, $parameters];
    }

    private $apiKey;
    private $base;
}
