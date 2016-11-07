<?php

namespace Perevorot\Seo\Traits;

use Perevorot\Seo\Models\Seo;
use Perevorot\Seo\Models\Settings;

trait SeoTrait
{
    /**
     * @var array
     */
    public $data = [];

    /**
     * @param $baseUrl
     * @return int
     */
    public static function findByUrlMask($baseUrl)
    {
        $items = [];
        $urls = self::getUrlMasks($baseUrl);
        $seo = Seo::where('url_mask', $baseUrl);

        foreach ($urls as $url) {
            $seo = $seo->orWhere('url_mask', $url);
        }

        $seo = $seo->get();

        foreach ($seo as $item) {
            $items[substr_count($item->url_mask, '*')] = $item;
        }

        if (sizeof($items) > 0) {
            $minKey = min(array_keys($items));

            return $items[$minKey];
        }

        $settings = Settings::instance();

        return $settings;
    }

    /**
     * @param $url
     * @return array
     */
    public static function getUrlMasks($url)
    {
        $urls = [];
        $i = sizeof(explode('/', $url));

        while ($i > 0) {
            $url = explode('/', $url);
            $url[$i - 1] = '*';
            $url = implode('/', $url);

            $urls[] = $url;

            $i--;
        }

        return $urls;
    }

    /**
     * @param $data
     */
    public function setData($data)
    {
        $this->data = array_merge($this->data, $data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
