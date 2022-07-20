<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompetenciesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('competencies', function(Blueprint $table)
		{
			$table->string('description', 100)->nullable()->comment('описание');
			$table->string('previous', 100)->nullable()->comment('предыдущие queue');
			$table->tinyInteger('fixed')->nullable()->comment('взаимосвязи зафиксированы');
			$table->nullableTimestamps();
			$table->softDeletes();
			$table->string('setter_login', 100)->nullable()->comment('логин пользователя, сохранившего запись');
			$table->dropForeign('competencies_FK');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('competencies', function (Blueprint $table) {
			$table->dropColumn(['description', 'previous', 'fixed', 'setter_login']);
			$table->dropTimestamps();
			$table->dropSoftDeletes();
			$table->foreign('post_id', 'competencies_FK')
      		->references('id')->on('department_post')
      		->onDelete('RESTRICT');
		  });
	}
}
