<?php namespace Perevorot\Page\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePerevorotPagePage4 extends Migration
{
    public function up()
    {
        Schema::table('perevorot_page_page', function($table)
        {
            $table->string('url_external', 255)->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('perevorot_page_page', function($table)
        {
            $table->dropColumn('url_external');
        });
    }
}
