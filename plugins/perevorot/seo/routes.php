<?php
    Route::get('robots.txt', function(){
        return response((string)\Perevorot\Seo\Models\Settings::instance()->robots, 200);
    });
