<?php namespace Perevorot\Prozorro\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePerevorotProzorroMaterials extends Migration
{
    public function up()
    {
        Schema::create('perevorot_prozorro_materials', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('title');
            $table->string('slug');
            $table->text('longread_ua');
            $table->text('longread_en');
            $table->integer('group_id');
            $table->integer('author_id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('perevorot_prozorro_materials');
    }
}
