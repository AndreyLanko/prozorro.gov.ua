<?php namespace Perevorot\Prozorro\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePerevorotProzorroCalendar3 extends Migration
{
    public function up()
    {
        Schema::table('perevorot_prozorro_calendar', function($table)
        {
            $table->string('location', 255)->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('perevorot_prozorro_calendar', function($table)
        {
            $table->string('location', 255)->nullable(false)->change();
        });
    }
}
