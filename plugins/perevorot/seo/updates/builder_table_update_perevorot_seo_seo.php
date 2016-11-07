<?php namespace Perevorot\Seo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePerevorotSeoSeo extends Migration
{
    public function up()
    {
        Schema::rename('perevorot_seo_parameters', 'perevorot_seo_seo');
    }
    
    public function down()
    {
        Schema::rename('perevorot_seo_seo', 'perevorot_seo_parameters');
    }
}
