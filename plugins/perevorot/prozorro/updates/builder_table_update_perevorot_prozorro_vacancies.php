<?php namespace Perevorot\Prozorro\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePerevorotProzorroVacancies extends Migration
{
    public function up()
    {
        Schema::table('perevorot_prozorro_vacancies', function($table)
        {
            $table->boolean('is_enabled')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('perevorot_prozorro_vacancies', function($table)
        {
            $table->dropColumn('is_enabled');
        });
    }
}
