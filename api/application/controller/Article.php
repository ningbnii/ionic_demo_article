<?php
namespace app\controller;

use app\model\Article as ArticleModel;
use app\model\Attention;
/**
* 文章
*/
class Article extends Wechat
{
	/**
	 * 文章列表
	 * @return [type] [description]
	 */
	public function getList()
	{
		$page = input('param.page', 1);

		$article = new ArticleModel;
		$list = $article->field('id,title,thumb,introduction')->paginate(10);
		return $this->success($list->all());
	}

	/**
	 * 文章详情
	 * @return [type] [description]
	 */
	public function detail()
	{
		$id = input('param.id');
		if(!$id){
			return $this->error('参数错误');
		}

		$article = new ArticleModel;
		$data = $article::get(function($query) use($id){
			$query->field('id,title,author,content,create_time')->where('id',$id);
		});
		$attention = $data->attention()->where(['status'=>1,'wxuser_id'=>$this->wxuserId])->find();
		if($attention){
			$data['attention'] = 1;
		}else{
			$data['attention'] = 0;
		}
		return $this->success($data);
	}

	/**
	 * 关注
	 * @return [type] [description]
	 */
	public function attention()
	{
		$id = input('param.id');
		$userId = $this->wxuserId;
		if(!$id){
			return $this->error('参数错误');
		}
		$attention = new Attention;
		$data = $attention->where(['article_id'=>$id, 'wxuser_id'=>$userId])->find();
		if(!$data){
			$insertData = [
				'wxuser_id'=>$userId,
				'article_id'=>$id,
				'status'=>1,
			];

			$result = $attention->data($insertData)->save();
		}else{
			if($data->status == 1){
				$data->status = 0;
				$data->save();
			}else{
				$data->status = 1;
				$data->save();
			}
		}
		return $this->success('操作成功');
	}

}