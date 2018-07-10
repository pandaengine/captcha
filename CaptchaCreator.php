<?php
namespace qingmvc\captcha;
use qing\container\ServiceCreator;
/**
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
class CaptchaCreator extends ServiceCreator{
	/**
	 * @see ServiceCreator::create()
	 */
	public function create(){
		$cap=new Captcha();
		//#view
		$view=new views\AdvView();
		$view->bgColor	=[255,255,255];
		$view->fontSize	=20;
		//$view->height	=20;
		$view->width	=180;
		//$view->ttfs	=['Yahoo','Airbus'];
		//不旋转，同一直线
		$view->rotate	=true;
		//#
		$cap->setView($view);
		$cap->setCreator(new creator\StringCreator());
		return $cap;
	}
}
?>