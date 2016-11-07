<?php

namespace Perevorot\Prozorro\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePerevorotProzorroMaterials2 extends Migration
{
    public function up()
    {
        Schema::table('perevorot_prozorro_materials', function($table)
        {
            $table->integer('group_id')->nullable()->change();
            $table->integer('author_id')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('perevorot_prozorro_materials', function($table)
        {
            $table->integer('group_id')->nullable(false)->change();
            $table->integer('author_id')->nullable(false)->change();
        });
    }
}
