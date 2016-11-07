<?php namespace Perevorot\Page\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePerevorotPagePage9 extends Migration
{
    public function up()
    {
        Schema::table('perevorot_page_page', function($table)
        {
            $table->boolean('is_disabled');
        });
    }
    
    public function down()
    {
        Schema::table('perevorot_page_page', function($table)
        {
            $table->dropColumn('is_disabled');
        });
    }
}
