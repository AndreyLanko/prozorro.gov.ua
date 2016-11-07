<?php namespace Perevorot\Page\Components\Traits;

use Redirect;

trait LocalePickerAjax
{
    public function onSwitchLocale()
    {
        if (!$locale = post('locale'))
            return;

        $this->translator->setLocale($locale);

        return Redirect::to($this->getSwitchUrl($locale));
    }
}
