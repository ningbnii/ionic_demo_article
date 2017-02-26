<?php
namespace app\controller;

use app\model\Wxuser as WxuserModel;

/**
* wxuser
*/
class Wxuser
{
    public function save(){
    	$openid = input('param.openid');
    	if(!$openid){
    		return json(['status'=>400, 'error'=>'参数错误']);
    	}
    	$wxuserData = cache('wxuser_' . $openid);
    	if(!$wxuserData){
			$wxuserData = WxuserModel::getByOpenid($openid);
    	}
		if(!$wxuserData){
			$data = [
				'openid'=>$openid,
				'thumb'=>input('param.thumb'),
				'name'=>input('param.name'),
				'sex'=>input('param.sex'),
				'create_time'=>time(),
				'update_time'=>time(),
			];
			$wxuserData = WxuserModel::create($data);
		}
		cache('wxuser_' . $openid, '1');
		return json(['status'=>200, 'data'=>['openid'=>$openid]]);
    }
}