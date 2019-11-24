<?php
/**
 * Sarcofag (http://sarcofag.com)
 *
 * @link       https://github.com/milsdev/sarcofag
 * @copyright  Copyright (c) 20012-2016 Mil's (http://www.mils.agency)
 * @license    http://sarcofag.com/license/mit
 */
namespace Sarcofag\Http;

use Psr\Http\Message\UriInterface;
use Sarcofag\Utility\UrlService;

class Response extends \Slim\Http\Response
{
    /**
     * @param UriInterface|string $url
     * @param null $status
     *
     * @return \Slim\Http\Response
     */
    public function withRedirect($url, $status = null): \Slim\Http\Response
    {
        return parent::withRedirect(UrlService::processUrl($url, ICL_LANGUAGE_CODE));
    }
}
