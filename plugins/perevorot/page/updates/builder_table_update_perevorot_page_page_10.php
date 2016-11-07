<?php namespace Perevorot\Page\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePerevorotPagePage10 extends Migration
{
    public function up()
    {
        Schema::table('perevorot_page_page', function($table)
        {
            $table->text('longread_en');
            $table->text('longread_ru');
            $table->renameColumn('longread', 'longread_ua');
        });
    }
    
    public function down()
    {
        Schema::table('perevorot_page_page', function($table)
        {
            $table->dropColumn('longread_en');
            $table->dropColumn('longread_ru');
            $table->renameColumn('longread_ua', 'longread');
        });
    }
}
