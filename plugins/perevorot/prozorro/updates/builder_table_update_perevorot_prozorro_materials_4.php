<?php namespace Perevorot\Prozorro\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePerevorotProzorroMaterials4 extends Migration
{
    public function up()
    {
        Schema::table('perevorot_prozorro_materials', function($table)
        {
            $table->text('longread_ua')->nullable()->change();
            $table->text('longread_en')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('perevorot_prozorro_materials', function($table)
        {
            $table->text('longread_ua')->nullable(false)->change();
            $table->text('longread_en')->nullable(false)->change();
        });
    }
}
