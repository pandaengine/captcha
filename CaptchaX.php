<?php
namespace qingmvc\captcha;
/**
 * @name Demos
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
class CaptchaX{
	/**
	 * 验证码组件
	 *
	 * @return Captcha
	 */
	public static function com($comid='captcha'){
		$coms=coms();
		if(!$coms->has($comid)){
			$coms->set($comid,['class'=>__NAMESPACE__.'\Captcha']);
		}
		return $coms->get($comid);
	}
	/**
	 * 验证验证码
	 * 
	 * @param string $captcha
	 * @param string $comid
	 * @return bool
	 */
	public static function check($captcha,$comid='captcha'){
		return static::com($comid)->check($captcha);
	}
	/**
	 * ['Yahoo','13misa','Airbus']
	 * 
	 * @return Captcha
	 */
	public static function advStr($fontSize=20,$width=180){
		$cap=static::com();
		//#view
		$path=__DIR__.'/ttfs';
		$view=new views\AdvView();
		$view->bgColor	=[255,255,255];
		$view->fontSize	=$fontSize;
		//$view->height	=20;
		$view->width	=$width;
		$view->ttfs		=[$path.'/Yahoo.ttf',$path.'/Airbus.ttf'];
		//不旋转，同一直线
		$view->rotate	=true;
		//#
		$cap->setView($view);
		$cap->setCreator(new creator\StringCreator());
		return $cap;
	}
	/**
	 * [20,210]
	 * 
	 * @return Captcha
	 */
	public static function str($fontSize=20,$width=180){
		$cap=static::com();
		//#view
		$view=new views\View();
		$view->bgColor	=[255,255,255];
		$view->fontSize	=$fontSize;
		//$view->height	=20;
		$view->width	=$width;
		//不旋转，同一直线
		$view->rotate	=true;
		//#
		$cap->setView($view);
		$cap->setCreator(new creator\StringCreator());
		return $cap;
	}
	/**
	 * @return Captcha
	 */
	public static function math($fontSize=16,$width=180){
		$cap=static::com();
		//#view
		$view=new views\View();
		$view->bgColor	=[255,255,255];
		$view->fontSize	=$fontSize;
		//$view->height	=20;
		$view->width	=$width;
		//不旋转，同一直线
		$view->rotate	=false;
		//#
		$cap->setView($view);
		$cap->setCreator(new creator\MathCreator());
		return $cap;
	}
	/**
	 * 
	 * [16,180]
	 * [20,210]
	 * 
	 * @param number $fontSize
	 * @param number $width
	 * @return Captcha
	 */
	public static function advMath($fontSize=20,$width=180){
		$cap=static::com();
		//#view
		$view=new views\AdvView();
		$view->dotNoise	=true;//点状干扰
		$view->lineNoise=true;//线状干扰
		
		$view->bgColor	=[255,255,255];
		$view->fontSize	=$fontSize;
		//$view->height	=20;
		//$view->height	=$fontSize*2.5;
		$view->width	=$width;
		//不旋转，同一直线
		$view->rotate	=false;
		$view->dots		='0123456789+-=?.';
		//#
		$cap->setView($view);
		$cap->setCreator(new creator\MathCreator());
		return $cap;
	}
}
?>