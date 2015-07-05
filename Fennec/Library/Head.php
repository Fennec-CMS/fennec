<?php
/**
 ************************************************************************
 * @copyright 2015 David Lima
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 ************************************************************************
 */
namespace Fennec\Library;

/**
 * Manage <head> tags
 *
 * @author David Lima
 * @version b0.1
 */
trait Head
{
    /**
     * <meta> tags
     *
     * @var array
     */
    public static $metaTags = array();

    /**
     * <script> tags
     *
     * @var array
     */
    public static $scripts = array();

    /**
     * <link> tags
     *
     * @var array
     */
    public static $links = array();

    /**
     * Content for <title> tag
     *
     * @var string
     */
    public static $title = "Untitled page";

    /**
     * Concat and return all tag generated
     *
     * @return string
     */
    public function createHead()
    {
        $head = $this->getHtmlMetaTags();
        $head .= $this->getHtmlTitle();
        $head .= $this->getScripts();
        $head .= $this->getLinks();
        echo PHP_EOL;
        return $head;
    }

    /**
     * Include <meta> tag
     *
     * @param array $data array containing all properties for this meta tag
     * @example addmetaTag(array('name' => 'robots', 'content' => 'nofollow,noindex'));
     */
    public function addMetaTag($identifier, array $data)
    {
        self::$metaTags[$identifier] = $data;
    }

    /**
     * Include <script> tag
     *
     * @param string|integer $identifier
     * @param string $src
     * @param string $type
     */
    public function addScript($identifier, $src, $type = "text/javascript")
    {
        self::$scripts[$identifier] = "<script type=\"{$type}\" src=\"{$src}\"></script>";
    }

    /**
     * Include <link> tag
     *
     * @param string|integer $identifier
     * @param string $href
     * @param string $type
     * @param string $rel
     */
    public function addLink($identifier, $href, $type, $rel)
    {
        self::$links[$identifier] = "<link href=\"{$href}\" type=\"{$type}\" rel=\"{$rel}\">";
    }

    /**
     * Include <link> tag with predefined CSS properties
     *
     * @param string|integer $identifier
     * @param string $href
     * @param string $type
     * @param string $rel
     */
    public function addCss($identifier, $href)
    {
        $this->addLink($identifier, $href, "text/css", "stylesheet");
    }

    /**
     * Defines <meta charset> tag
     *
     * @param string $charset
     */
    public function setCharset($charset)
    {
        $this->addMetaTag('charset', array(
            'charset' => $charset
        ));
    }

    /**
     * Defines <title> tag value
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        self::$title = $title;
    }

    /**
     * Concat and return all <script> tags
     *
     * @return string
     */
    private function getScripts()
    {
        return implode(PHP_EOL, self::$scripts) . PHP_EOL;
    }

    /**
     * Concat and return all <link> tags
     *
     * @return string
     */
    private function getLinks()
    {
        return implode(PHP_EOL, self::$links) . PHP_EOL;
    }

    /**
     * Concat and return all <meta> tags
     *
     * @return string
     */
    private function getHtmlMetaTags()
    {
        $html = array();
        foreach (self::$metaTags as $metaTag) {
            $meta = "";
            $meta .= '<meta';
            foreach ($metaTag as $key => $val) {
                $meta .= " $key=\"$val\"";
            }
            $meta .= '>';
            $html[] = $meta;
        }
        return implode(PHP_EOL, $html) . PHP_EOL;
    }

    /**
     * Return HTML <title> tag
     *
     * @return string
     */
    private function getHtmlTitle()
    {
        return "<title>" . self::$title . "</title>" . PHP_EOL;
    }
}
