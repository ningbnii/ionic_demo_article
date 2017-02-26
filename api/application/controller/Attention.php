<?php
namespace app\controller;

use app\model\Attention as AttentionModel;
/**
* 关注
*/
class Attention extends Wechat
{
	public function getList()
	{
		$page = input('param.page', 1);
		$attentionModel = new AttentionModel;
		$list = AttentionModel::field('id,article_id')->where(['status'=>1,'wxuser_id'=>$this->wxuserId])->paginate(10);
		$list = $list->all();
		$data = [];
		foreach ($list as $key => $value) {
			$data[] = [
				'article_id'=>$value->article_id,
				'title'=>$value->article->title
			];
		}
		if($data){
			return $this->success($data);
		}else{
			return $this->error('暂无数据');
		}
	}
}