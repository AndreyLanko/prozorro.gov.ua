<?php namespace Perevorot\Page\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePerevorotPagePage extends Migration
{
    public function up()
    {
        Schema::create('perevorot_page_page', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('parent_id')->nullable()->unsigned();
            $table->integer('nest_left')->nullable()->unsigned();
            $table->integer('nest_right')->nullable()->unsigned();
            $table->string('title', 255);
            $table->string('url', 255);
            $table->boolean('is_target_blank');
            $table->smallInteger('type');
            $table->string('alias', 255);
            $table->integer('redirect_id')->unsigned();
            $table->boolean('is_active');
            $table->string('layout', 255);
            $table->string('seo_title', 255);
            $table->text('seo_description');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('perevorot_page_page');
    }
}

