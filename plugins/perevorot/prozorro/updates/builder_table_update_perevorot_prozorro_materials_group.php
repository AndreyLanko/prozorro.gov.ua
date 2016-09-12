<?php

namespace Perevorot\Prozorro\Updates;

use October\Rain\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePerevorotProzorroMaterialsGroup extends Migration
{
    public function up()
    {
        Schema::table('perevorot_prozorro_materials_group', function(Blueprint $table)
        {
            $table->string('slug')->unique()->change();
        });
    }
    
    public function down()
    {
    }
}
