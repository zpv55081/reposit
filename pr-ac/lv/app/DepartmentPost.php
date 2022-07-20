<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class DepartmentPost extends Model {

	protected $table = 'department_post';    

    public $timestamps = false;

	//Массивчик должностей
	public static function selectPosts($db)
	{
		$sql="SELECT `id`, `id_department`, `name` FROM `department_post` WHERE `is_deleted`='0' ORDER BY `name`";
		$query=mysql_query($sql,$db);
		while($postrow=mysql_fetch_assoc($query)){
		$posts[$postrow['id']]=$postrow;
		}
		return $posts;
	}
}
