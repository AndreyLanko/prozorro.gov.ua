<?php namespace Perevorot\Prozorro\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePerevorotProzorroCalendar extends Migration
{
    public function up()
    {
        Schema::create('perevorot_prozorro_calendar', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('title');
            $table->string('slug');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('phone');
            $table->string('email');
            $table->string('location');
            $table->text('description');
            $table->text('programm');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('perevorot_prozorro_calendar');
    }
}
