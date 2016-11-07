<?php namespace Perevorot\Seo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePerevorotSeoParameters extends Migration
{
    public function up()
    {
        Schema::create('perevorot_seo_parameters', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('url_mask');
            $table->string('title');
            $table->text('description');
            $table->text('keywords');
            $table->string('canonical');
            $table->text('meta_tags');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('perevorot_seo_parameters');
    }
}
