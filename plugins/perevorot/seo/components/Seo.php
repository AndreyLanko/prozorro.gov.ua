<?php

namespace Perevorot\Seo\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;
use October\Rain\Support\Facades\Twig;
use Perevorot\Seo\Models\Settings;
use Perevorot\Seo\Models\Seo as SeoModel;
use RainLab\Translate\Models\Locale;

class Seo extends ComponentBase
{
    use \Perevorot\Seo\Traits\SeoTrait;

    public function componentDetails()
    {
        return [
            'name'        => 'Seo Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRender()
    {
        $settings = Settings::instance();

        $url = Request::path();
        $url = trim($url, '/');

        $locales = Locale::isEnabled()->get(['code'])->toArray();
        $locales = array_pluck($locales, 'code');
        $locales = trim(implode('|', $locales), '|');
        $pattern = '/^(' . $locales . ')\/?/';

        $url = '/' . preg_replace($pattern, '', $url);
        
        $seo = SeoModel::findByUrlMask($url);

        Event::fire('seo.handle', [
            $seo
        ]);

        $this->setTemplate($seo, 'title');
        $this->setTemplate($seo, 'description');
        $this->setTemplate($seo, 'keywords');
        $this->setTemplate($seo, 'canonical');

        $this->page['settings'] = $settings;
        $this->page['seo'] = $seo;
    }

    /**
     * @param $seo
     * @param $variable
     */
    private function setTemplate($seo, $variable)
    {
        if ($seo->{$variable}) {
            $patternTemplate = "/seo\.([0-9A-Za-z\_]+)\[[\'|\"]([0-9A-Za-z\-\_\/\*]+)[\'|\"]\]/";
            $patternDefault = "/default\.([0-9A-Za-z]+)/";

            $this->processParseTemplate($seo, $variable, $patternTemplate, $patternDefault);

            while (preg_match($patternTemplate, $seo->{$variable}) || preg_match($patternDefault, $seo->{$variable})) {
                $this->processParseTemplate($seo, $variable, $patternTemplate, $patternDefault);
            }
        }
    }

    private function processParseTemplate(&$seo, $variable, $patternTemplate, $patternDefault)
    {
        $params = $this->parseTemplate($patternTemplate, $seo->{$variable});
        $paramsDefault = $this->parseDefault($patternDefault, $seo->{$variable});

        $seo->setData([
            "seo" => $params,
            "default" => $paramsDefault,
        ]);

        $seo->{$variable} = html_entity_decode(Twig::parse($seo->{$variable}, $seo->getData()), ENT_QUOTES);
    }

    /**
     * @param $template
     * @return array
     */
    private function parseDefault($pattern, $template)
    {
        $matches = [];

        preg_match_all($pattern, $template, $matches);

        $parameters = [];

        foreach ($matches[1] as $key => $match) {
            $parameters[$match] = Settings::instance()->{$match};
        }

        return $parameters;
    }

    /**
     * @param $template
     * @return array
     */
    private function parseTemplate($pattern, $template)
    {
        //Парсинг шаблона
        $matches = [];

        preg_match_all($pattern, $template, $matches);

        $parameters = [];

        foreach ($matches[0] as $key => $match) {
            $parameters[str_replace('seo.', '', $match)] = [
                "field" => $matches[1][$key],
                "url_mask" => $matches[2][$key],
            ];
        }

        //Получение результата
        $seo = SeoModel::orderBy('id');

        foreach ($parameters as $parameter) {
            $seo->orWhere('url_mask', $parameter['url_mask']);
        }

        $seo = $seo->get(array_merge(array_column($parameters, "field"), ["url_mask"]))->keyBy('url_mask');

        //Перевод результата в нужный вид
        foreach ($parameters as $key => $parameter) {
            $parameters[$parameter['field']][$parameter['url_mask']] = isset($seo[$parameter['url_mask']])?$seo[$parameter['url_mask']]->{$parameter['field']}:'';

            unset($parameters[$key]);
        }

        return $parameters;
    }
}
