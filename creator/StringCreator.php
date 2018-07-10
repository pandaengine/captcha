<?php
namespace qingmvc\captcha\creator;
/**
 * 字符串
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
class StringCreator implements CreatorInterface{
	/**
	 * 验证码字符集合
	 * [去掉1il0o等难识别字符]
	 * 
	 * @var string
	 */
	public $chars='2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY';
	/**
	 * 字符长度
	 *
	 * @var number
	 */
	public $length=4;
	/**
	 * 
	 */
	public function create(){
		$len=strlen($this->chars);
		$str="";
		for($i=0;$i<$this->length;$i++){
			//从字符集合中随机取一个
			$str.=$this->chars[mt_rand(0,$len-1)];
		}
		return [$str,$str];
	}
}
?>