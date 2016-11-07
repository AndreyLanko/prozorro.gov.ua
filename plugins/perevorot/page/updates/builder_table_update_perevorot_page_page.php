<?php namespace Perevorot\Page\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePerevorotPagePage extends Migration
{
    public function up()
    {
        Schema::table('perevorot_page_page', function($table)
        {
            $table->integer('nest_depth')->nullable()->unsigned();
        });
    }

    public function down()
    {
        Schema::table('perevorot_page_page', function($table)
        {
            $table->dropColumn('nest_depth');
        });
    }
}

