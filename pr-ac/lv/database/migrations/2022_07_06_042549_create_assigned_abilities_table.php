<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignedAbilitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('assigned_abilities', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->integer('au_id')->unsigned()->comment('id_au из таблицы au');
			$table->integer('abilities_id')->unsigned()->comment('id из таблицы abilities');
			$table->foreign('abilities_id', 'assigned_abilities_abilities_id_foreign')
			->references('id')->on('abilities')->onDelete('RESTRICT');
			$table->tinyInteger('is_deleted')->nullable()->default(NULL);
			$table->softDeletes();
			$table->timestamps();
			$table->string('setter_login', 100)->nullable()->default(NULL)->comment('логин пользователя, сохранившего запись');
			$table->engine = 'InnoDB';
			$table->charset = 'utf8';
		});

		DB::statement("ALTER TABLE assigned_abilities comment 'назначенные способности (типы работ)'");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('assigned_abilities');
	}

}
