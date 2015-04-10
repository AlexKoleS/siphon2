<?php
namespace Icecave\Siphon\Player;


/**
 * Information about player images.
 *
 * @api
 */
interface ImageInterface
{
    /**
     * Get the player ID.
     *
     * @return string The player ID.
     */
    public function playerId();

    /**
     * Get the URL for the player's image.
     *
     * @return string The image URL.
     */
    public function url();

    /**
     * Get the URL for the player's thumbnail image.
     *
     * @return string The thumbnail image URL.
     */
    public function thumbnailUrl();
}
