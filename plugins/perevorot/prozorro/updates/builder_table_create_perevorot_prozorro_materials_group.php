<?php namespace Perevorot\Prozorro\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePerevorotProzorroMaterialsGroup extends Migration
{
    public function up()
    {
        Schema::create('perevorot_prozorro_materials_group', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('title');
            $table->string('slug');
            $table->integer('sort_order')->default(1);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('perevorot_prozorro_materials_group');
    }
}
