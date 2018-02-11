<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHcMenuTypeTranslationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hc_menu_type_translation', function(Blueprint $table)
		{
            $table->increments('count');
            $table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->uuid('record_id');
            $table->string('language_code', 2);

            $table->string('label');
            $table->text('description')->nullable();

            $table->unique(['record_id', 'language_code']);

            $table->foreign('record_id')->references('id')->on('hc_menu_type')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('language_code')->references('iso_639_1')->on('hc_language');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hc_menu_type_translation');
	}

}
