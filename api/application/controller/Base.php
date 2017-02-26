<?php
namespace app\controller;

use think\Request;
/**
* 基类
* @author <296720094@qq.com http://wxubluo.com>
*/
class Base
{

	public function __construct()
	{
		$array = array(
			'http://wxbuluo.tunnel.hteen.cn/',
		);
		if(isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'] , $array)){
			header('Access-Control-Allow-Origin:' . $_SERVER['HTTP_ORIGIN']);
			header('Access-Control-Allow-Methods: *');
			header('Access-Control-Allow-Headers: x-requested-with,authorization');
		}

	}

	/**
	 * 操作成功，返回信息
	 * @Author   chenning
	 * @DateTime 2017-01-17T11:30:55+0800
	 * @param    [type]                   $data [description]
	 * @return   [type]                         [description]
	 */
	protected function success($data)
	{
		return json(['status'=>200, 'data'=>$data]);
	}

	/**
	 * 操作失败，返回错误信息
	 * @Author   chenning
	 * @DateTime 2017-01-17T11:31:50+0800
	 * @param    [type]                   $data [description]
	 * @return   [type]                         [description]
	 */
	protected function error($data)
	{
		return json(['status'=>400, 'error'=>$data]);
	}

}