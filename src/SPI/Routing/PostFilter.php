<?php

namespace Sarcofag\SPI\Routing;


class PostFilter implements PostFilterInterface
{
    /**
     * @inheritdoc
     */
    public function filter(\WP_Post $post)
    {
        return true;
    }
}