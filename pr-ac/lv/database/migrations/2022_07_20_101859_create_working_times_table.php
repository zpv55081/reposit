<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkingTimesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('working_time', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->integer('group_id')->unsigned()->comment('id_group из таблицы group');
			$table->date('date');
			$table->string('hours', 100)->nullable()->default(NULL)->comment('0 = с 00.00 до 01.00; 1 = с 01.00 до 02.00 и т.д.');
			$table->timestamps();
			$table->softDeletes();
			$table->string('setter_login', 100)->nullable()->comment('логин пользователя, сохранившего запись');
			$table->engine = 'InnoDB';
			$table->charset = 'utf8';
		});
		DB::statement("ALTER TABLE working_time comment 'Рабочие часы в городах'");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('working_time');
	}
}
