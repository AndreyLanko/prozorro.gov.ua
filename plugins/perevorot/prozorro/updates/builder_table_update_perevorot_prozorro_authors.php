<?php namespace Perevorot\Prozorro\Updates;

use Illuminate\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePerevorotProzorroAuthors extends Migration
{
    public function up()
    {
        Schema::table('perevorot_prozorro_authors', function(Blueprint $table)
        {
            $table
                ->string('slug')
                ->unique()
                ->change()
            ;
        });
    }
    
    public function down()
    {
    }
}
