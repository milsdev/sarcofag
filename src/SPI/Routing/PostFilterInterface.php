<?php

namespace Sarcofag\SPI\Routing;

interface PostFilterInterface
{
    /**
     * @param \WP_Post $post
     * @return bool
     */
    public function filter(\WP_Post $post);
}