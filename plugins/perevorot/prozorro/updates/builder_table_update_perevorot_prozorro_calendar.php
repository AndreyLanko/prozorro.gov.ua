<?php namespace Perevorot\Prozorro\Updates;

use October\Rain\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePerevorotProzorroCalendar extends Migration
{
    public function up()
    {
        Schema::table('perevorot_prozorro_calendar', function(Blueprint $table)
        {
            $table->string('slug')->unique()->change();
        });
    }
    
    public function down()
    {
    }
}
