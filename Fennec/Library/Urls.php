<?php
/**
 ************************************************************************
 * @copyright 2015 David Lima
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0) 
 ************************************************************************
 */
namespace Fennec\Library;

/**
 * Trait used to manage URLs
 *
 * @author David Lima
 * @version b1.0
 */
trait Urls
{

    /**
     * Converts a URL into a slug
     * Based on Matheo Spinelli code <matteo@cubiq.org>
     *
     * @param string $string
     * @return string
     */
    public function toSlug($string)
    {
        $url = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        $url = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $url);
        $url = strtolower(trim($url, '-'));
        $url = preg_replace("/[\/_|+ -]+/", '-', $url);

        return $url;
    }
}
