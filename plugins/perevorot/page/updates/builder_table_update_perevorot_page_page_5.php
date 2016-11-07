<?php namespace Perevorot\Page\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePerevorotPagePage5 extends Migration
{
    public function up()
    {
        Schema::table('perevorot_page_page', function($table)
        {
            $table->integer('alias_page_id');
            $table->renameColumn('alias', 'cms_page_id');
            $table->dropColumn('redirect_id');
        });
    }
    
    public function down()
    {
        Schema::table('perevorot_page_page', function($table)
        {
            $table->dropColumn('alias_page_id');
            $table->renameColumn('cms_page_id', 'alias');
            $table->integer('redirect_id')->unsigned();
        });
    }
}
