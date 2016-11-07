<?php namespace Perevorot\Prozorro\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePerevorotProzorroCalendar2 extends Migration
{
    public function up()
    {
        Schema::table('perevorot_prozorro_calendar', function($table)
        {
            $table->boolean('is_enabled')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('perevorot_prozorro_calendar', function($table)
        {
            $table->dropColumn('is_enabled');
        });
    }
}
