<?php
namespace Icecave\Siphon;

/**
 * Constructs a fully-qualified feed URL from a resource name and parameter set.
 */
interface UrlBuilderInterface
{
    /**
     * Build a feed URL.
     *
     * @param string               $resource   The path to the feed.
     * @param array<string, mixed> $parameters Additional parameters to pass.
     *
     * @return string The fully-qualified feed URL.
     */
    public function build($resource, array $parameters);

    /**
     * Extract the resource and parameters from a fully-qualified URL.
     *
     * @param string $url The URL.
     *
     * @return tuple<string, array> A 2-tuple containing the resource and parameters.
     */
    public function extract($url);
}
