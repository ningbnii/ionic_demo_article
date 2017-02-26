<?php
namespace app\controller;

use app\model\Wxuser;

/**
* 微信基类
*/
class Wechat extends Base
{
	protected $wxuserId;

	public function __construct()
	{
		parent::__construct();
		$this->wxuserId = $this->checkOpenid();
	}

	/**
	 * 检查是否有openid传过来
	 * @return [type] [description]
	 */
	protected function checkOpenid()
	{
		$openid = input('param.openid','');
		if(!$openid){
			echo json_encode(['status'=>400,'error'=>'微信授权失败']);exit;
		}
		$wxuserId = cache('wxuserId_' . $openid);
		if(!$wxuserId){
			$wxuserData = Wxuser::getByOpenid($openid);
			
			if(!$wxuserData){
				echo json_encode(['status'=>400,'error'=>'微信授权失败']);exit;
			}
			$wxuserId = $wxuserData->id;
			cache('wxuserId_' . $openid, $wxuserId);
		}		
		return $wxuserId;
	}	
}