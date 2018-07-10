<?php
namespace qingmvc\captcha\creator;
/**
 *
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
interface CreatorInterface{
	/**
	 * 
	 * @return [验证码,答案]
	 */
	public function create();
}
?>