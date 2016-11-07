<?php namespace Perevorot\Prozorro\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePerevorotProzorroPartners extends Migration
{
    public function up()
    {
        Schema::create('perevorot_prozorro_partners', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('hyperlink');
            $table->integer('sort_order')->default(1);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('perevorot_prozorro_partners');
    }
}
