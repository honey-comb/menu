<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddFkOnHcMenuParentField extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('hc_menu', function(Blueprint $table)
		{
            $table->foreign('parent_id')->references('id')
                ->on('hc_menu')
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
        Schema::table('hc_menu', function(Blueprint $table)
        {
            $table->dropForeign(['parent_id']);
        });
	}

}
