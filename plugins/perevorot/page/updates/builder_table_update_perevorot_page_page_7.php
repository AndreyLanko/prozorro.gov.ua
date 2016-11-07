<?php namespace Perevorot\Page\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePerevorotPagePage7 extends Migration
{
    public function up()
    {
        Schema::table('perevorot_page_page', function($table)
        {
            $table->text('longread');
        });
    }
    
    public function down()
    {
        Schema::table('perevorot_page_page', function($table)
        {
            $table->dropColumn('longread');
        });
    }
}
