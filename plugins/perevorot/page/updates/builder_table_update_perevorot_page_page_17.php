<?php namespace Perevorot\Page\Updates;

use Illuminate\Support\Facades\DB;
use October\Rain\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePerevorotPagePage17 extends Migration
{
    public function up()
    {
        Schema::table('perevorot_page_page', function(Blueprint $table)
        {
            DB::statement('ALTER TABLE `perevorot_page_page` CHANGE `longread_ru` `longread_ru` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;');
            DB::statement('ALTER TABLE `perevorot_page_page` CHANGE `longread_ua` `longread_ua` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;');
            DB::statement('ALTER TABLE `perevorot_page_page` CHANGE `longread_en` `longread_en` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;');
        });
    }
    
    public function down()
    {

    }
}
