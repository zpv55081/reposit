<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkingTime extends Model {

	protected $table = 'working_time';

	protected $fillable = ['group_id', 'date', 'hours', 'created_at', 'updated_at', 'deleted_at', 'setter_login'];

}
