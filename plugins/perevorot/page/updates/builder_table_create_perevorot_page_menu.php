<?php namespace Perevorot\Page\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePerevorotPageMenu extends Migration
{
    public function up()
    {
        Schema::create('perevorot_page_menu', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->string('title', 255);
            $table->string('alias', 255);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('perevorot_page_menu');
    }
}
