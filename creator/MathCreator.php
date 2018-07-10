<?php
namespace qingmvc\captcha\creator;
/**
 * 数学公式
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
class MathCreator implements CreatorInterface{
	/**
	 * 创建公式
	 * 
	 * @see CreatorInterface::create()
	 */
	public function create(){
		//#操作符号
		$ops =['+','-','*'];
		$op  =$ops[mt_rand(0,2)];
	
		if($op=='*'){
			//#乘法操作符|九九乘法表
			$a=mt_rand(1,10);
			$b=mt_rand(1,10);
		}else{
			//#加减法
			$a=mt_rand(10,99);
			$b=mt_rand(0,10);
		}
		$ans='';
		switch($op){
			case '+':
				$ans=$a+$b;
				break;
			case '-':
				$ans=$a-$b;
				break;
			case '*':
				$ans=$a*$b;
				break;
		}
		$str = "{$a} {$op} {$b} = ? ";
		return [$str,$ans];
	}
}
?>