<?php
/**
 * Sarcofag (http://sarcofag.com)
 *
 * @link       https://github.com/milsdev/sarcofag
 * @copyright  Copyright (c) 20012-2016 Mil's (http://www.mils.agency)
 * @license    http://sarcofag.com/license/mit
 */
namespace Sarcofag\Utility;

class UrlService
{
    protected static function proceedAbsoluteUrl(string $url, bool $absolute)
    {
        if ($absolute === false) return $url;
        $proto = 'http://';
        $hostName = $_SERVER['SERVER_NAME'];
        if (!empty($_SERVER['HTTPS'])) {
            $proto = 'https://';
        }

        return $proto . $hostName . $url;
    }

    public static function processUrl(
        string $url,
        string $currentLanguage = null,
        bool $absolute = false,
        array $queryParams = []
    ): string {
        if (defined('ICL_LANGUAGE_CODE') && is_null($currentLanguage)) {
            $currentLanguage = ICL_LANGUAGE_CODE;
        }

        $defaultLanguage = defined('ICL_DEFAULT_LANGUAGE_CODE') ? ICL_DEFAULT_LANGUAGE_CODE : 'uk';

        if ($currentLanguage == $defaultLanguage) {
            $processedUrl = static::proceedAbsoluteUrl($url, $absolute);
        } else if (strpos($url, '/'.$currentLanguage) !== false) {
            $processedUrl = static::proceedAbsoluteUrl($url, $absolute);
        } else {
            $processedUrl = static::proceedAbsoluteUrl(
                sprintf('/%s/%s', $currentLanguage, ltrim($url, '/')),
                $absolute
            );
        }

        $queryString = parse_url($processedUrl, PHP_URL_QUERY);
        $parsedQueryParams = [];

        if (!is_null($queryString)) {
            parse_str($queryString, $parsedQueryParams);
        }

        $newQueryString = http_build_query(array_merge($parsedQueryParams, $queryParams));

        if (!is_null($queryString)) {
            return str_replace($queryString, $newQueryString, $processedUrl);
        }

        return $processedUrl . (!empty($newQueryString) ? '?' . $newQueryString : $newQueryString);
    }
}
