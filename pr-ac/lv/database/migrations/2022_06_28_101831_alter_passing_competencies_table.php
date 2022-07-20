<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPassingCompetenciesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('passing_competencies', function(Blueprint $table)
		{
			$table->softDeletes();
			$table->nullableTimestamps();
			$table->string('setter_login', 100)->nullable()->comment('логин пользователя, сохранившего запись');
			$table->timestamp('request_date')->after('au_id')->nullable()->comment('дата запроса обучения студентом');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('passing_competencies', function (Blueprint $table) {
			$table->dropSoftDeletes();
			$table->dropTimestamps();
			$table->dropColumn(['request_date', 'setter_login']);
		  });
	}
}
