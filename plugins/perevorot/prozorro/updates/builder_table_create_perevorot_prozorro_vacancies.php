<?php namespace Perevorot\Prozorro\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePerevorotProzorroVacancies extends Migration
{
    public function up()
    {
        Schema::create('perevorot_prozorro_vacancies', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('title');
            $table->text('description');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('perevorot_prozorro_vacancies');
    }
}
