<?php
namespace Icecave\Siphon\Player;


/**
 * Information about player images.
 */
class Image implements ImageInterface
{
    public function __construct(
        $playerId,
        $url,
        $thumbnailUrl
    ) {
        $this->playerId     = $playerId;
        $this->url          = $url;
        $this->thumbnailUrl = $thumbnailUrl;
    }
    /**
     * Get the player ID.
     *
     * @return string The player ID.
     */
    public function playerId()
    {
        return $this->playerId;
    }

    /**
     * Get the URL for the player's image.
     *
     * @return string The image URL.
     */
    public function url()
    {
        return $this->url;
    }

    /**
     * Get the URL for the player's thumbnail image.
     *
     * @return string The thumbnail image URL.
     */
    public function thumbnailUrl()
    {
        return $this->thumbnailUrl;
    }

    private $playerId;
    private $url;
    private $thumbnailUrl;
}
