<?php
namespace app\model;

use think\Model;

/**
* 
*/
class Attention extends Model
{
	protected $auto = ['update_time'];
	protected $insert = ['create_time'];

	public function article()
	{
		return $this->belongsTo('article')->field('title');
	}
}