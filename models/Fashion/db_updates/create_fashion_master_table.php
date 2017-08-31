<?php

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateFashionMasterTable extends Migration
{

    public function up()
    {
        Schema::create('fashion_master', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('slug')->index();
            // $table->string('feature_image')->unique();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fashion_master');
    }

}
