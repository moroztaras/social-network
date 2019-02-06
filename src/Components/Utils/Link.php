<?php

namespace App\Components\Utils;

use Symfony\Component\HttpFoundation\RedirectResponse;

class Link
{
    /**
     * Returns a RedirectResponse to the given URL.
     *
     * @param string $url    The URL to redirect to
     * @param int    $status The status code to use for the Response
     *
     * @return RedirectResponse
     */
    public static function redirect($url, $status = 302)
    {
        return new RedirectResponse($url, $status);
    }

    public static function domain()
    {
        $pu = parse_url($_SERVER['REQUEST_URI']);

        return $pu['scheme'].'://'.$pu['host'];
    }

    public static function addHttp($url, $scheme = 'http://')
    {
        return null === parse_url($url, PHP_URL_SCHEME) ? $scheme.$url : $url;
    }

    public static function addWww($url)
    {
        $url = trim($url);
        $bits = parse_url($url);
        $newHost = 'www.' !== substr($bits['host'], 0, 4) ? 'www.'.$bits['host'] : $bits['host'];
        $bits['path'] = $bits['path'] ? $bits['path'] : '';
        $url = $bits['scheme'].'://'.$newHost.(isset($bits['port']) ? ':'.$bits['port'] : '').$bits['path'].(!empty($bits['query']) ? '?'.$bits['query'] : '');

        return $url;
    }

    public static function addHttpAndWww($url, $scheme = null)
    {
        $url = trim($url);
        $url = static::addHttp($url, $scheme);
        $url = static::addWww($url);
        if ('https://' == $scheme) {
            $url = str_replace('http://', $scheme, $url);
        }

        return $url;
    }
}
