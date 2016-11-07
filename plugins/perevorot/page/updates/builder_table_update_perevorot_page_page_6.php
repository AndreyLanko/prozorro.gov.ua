<?php namespace Perevorot\Page\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePerevorotPagePage6 extends Migration
{
    public function up()
    {
        Schema::table('perevorot_page_page', function($table)
        {
            $table->renameColumn('is_active', 'is_hidden');
            $table->renameColumn('seo_title', 'meta_title');
            $table->renameColumn('seo_description', 'meta_description');
        });
    }
    
    public function down()
    {
        Schema::table('perevorot_page_page', function($table)
        {
            $table->renameColumn('is_hidden', 'is_active');
            $table->renameColumn('meta_title', 'seo_title');
            $table->renameColumn('meta_description', 'seo_description');
        });
    }
}
