<?php

namespace App\Helpers;

class Tools
{
    public static function manageCUrl($params, $header, $url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        if (count($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        if (count($params)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public static function getFirstMatch($regex, $page)
    {
        preg_match($regex, $page, $match);
        return trim($match[1]);
    }

    public static function getAllMatches($regex, $page)
    {
        preg_match_all($regex, $page, $match);
        return $match;
    }

    public static function uniteUrls($url)
    {
        $lastChar = substr($url, -1);
        if ($lastChar != "/"){
            $url .= "/";
        }
        return $url;
    }

    public static function checkUrlType($url)
    {
        if (strpos($url, DOMAIN) === false) {
            return self::uniteUrls($url);
        } else {
            $slug = explode(DOMAIN, $url);
            return self::uniteUrls($slug[1]);
        }
    }

    public static function getUrl()
    {
        return $_SERVER['REQUEST_URI'];
    }
}
