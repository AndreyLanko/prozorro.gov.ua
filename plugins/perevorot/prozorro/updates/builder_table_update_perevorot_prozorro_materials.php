<?php namespace Perevorot\Prozorro\Updates;

use October\Rain\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePerevorotProzorroMaterials extends Migration
{
    public function up()
    {
        Schema::table('perevorot_prozorro_materials', function(Blueprint $table)
        {
            $table->string('slug')->unique()->change();

            $table->integer('group_id')->unsigned()->nullable()->change();
            $table->integer('author_id')->unsigned()->nullable()->change();
        });

        Schema::table('perevorot_prozorro_materials', function(Blueprint $table)
        {
            $table->foreign('group_id')->references('id')->on('perevorot_prozorro_materials_group')->onDelete('SET NULL');
            $table->foreign('author_id')->references('id')->on('perevorot_prozorro_authors')->onDelete('SET NULL');
        });
    }
    
    public function down()
    {
    }
}
