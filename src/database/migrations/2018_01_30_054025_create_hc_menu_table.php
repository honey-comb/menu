<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHcMenuTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hc_menu', function(Blueprint $table) {
            $table->increments('count');
            $table->uuid('id')->unique();
            $table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->datetime('deleted_at')->nullable();

            $table->uuid('language_code')->index();
            $table->uuid('type_id')->index();
            $table->uuid('parent_id')->index()->nullable();

            $table->string('label');
            $table->enum('target', ['url', 'page']);
            $table->integer('sequence')->default(0);
            $table->string('icon')->nullable();
            $table->string('url', 2000)->nullable();
            $table->string('url_text')->nullable();
            $table->enum('url_target', ['_self', '_blank'])->nullable();

            $table->foreign('type_id')->references('id')
                ->on('hc_menu_type')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');

            $table->foreign('language_code')->references('iso_639_1')
                ->on('hc_languages')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hc_menu');
    }

}
