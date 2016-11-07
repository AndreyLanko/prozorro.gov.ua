<?php namespace Perevorot\Page\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePerevorotPagePage2 extends Migration
{
    public function up()
    {
        Schema::table('perevorot_page_page', function($table)
        {
            $table->integer('nest_left')->nullable(false)->change();
            $table->integer('nest_right')->nullable(false)->change();
            $table->integer('nest_depth')->nullable(false)->change();
        });
    }
    
    public function down()
    {
        Schema::table('perevorot_page_page', function($table)
        {
            $table->integer('nest_left')->nullable()->change();
            $table->integer('nest_right')->nullable()->change();
            $table->integer('nest_depth')->nullable()->change();
        });
    }
}
