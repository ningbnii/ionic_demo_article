<?php
namespace app\model;

use think\Model;

/**
* 文章
*/
class Article extends Model
{
	public function getThumbAttr($value='')
	{
		return config('img_site') . $value;
	}

	public function getCreateTimeAttr($value='')
	{
		return date('Y年m月d日', $value);
	}

	public function attention()
	{
		return $this->hasMany('Attention')->field('id');
	}

}