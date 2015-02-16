<?php
namespace Icecave\Sid;

/**
 * Build a feed URL.
 */
interface UrlBuilderInterface
{
    /**
     * Build a feed URL.
     *
     * @param string               $resource   The path to the feed.
     * @param array<string, mixed> $parameters Additional parameters to pass.
     *
     * @return string The full URL.
     */
    public function build($resource, array $parameters);
}
