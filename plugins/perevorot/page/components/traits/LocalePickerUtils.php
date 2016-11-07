<?php namespace Perevorot\Page\Components\Traits;

use RainLab\Translate\Models\Locale as LocaleModel;
use Request;

trait LocalePickerUtils
{
    private function parseLocales()
    {
        $localesEnables=LocaleModel::listEnabled();
        $locales=[];

        foreach($localesEnables as $code=>$localeName)
        {
            if($this->property('type')!='select' && $this->property('is_current_hidden') && $code==$this->activeLocale)
                continue;

            $locales[$code]=[
                'name'=>$localeName,
                'url'=>$this->getSwitchUrl($code)
            ];
        }

        return $locales;
    }

    private function getSwitchUrl($locale)
    {
        $url=[];
        $originalUrl=Request::path()!='/' ? Request::path() : '';

        foreach($this->localeCodes as $code)
        {
            if(starts_with($originalUrl, $code.'/'))
                $originalUrl=substr($originalUrl, 3);

            if($originalUrl==$code)
                $originalUrl='';
        }

        if($locale!=$this->translator->getDefaultLocale())
            $url[]=$locale;

        if($this->property('is_switch_same_url')!='index' && !empty($originalUrl))
            $url[]=$originalUrl;

        return !empty($url) ? '/'.implode('/', $url) : '/';
    }
}
