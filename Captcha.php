<?php
namespace qingmvc\captcha;
use qing\com\Component;
/**
 * 验证码组件
 * 
 * @bug 多处使用冲突 2018.07.02
 * - 多个地方使用同一个验证码组件时，值被覆盖的问题？导致一处刷新另一处验证出错的问题
 * - 解决方案：两处使用不同的组件名称即可，创建多个不同的组件实例即可
 * - 不同组件设置不同$sessionName也可
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
class Captcha extends Component{
	/**
	 * 验证码Id
	 * - 和组件名称绑定，避免多处使用冲突
	 * - captcha_captcha2
	 * 
	 * @var string
	 */
	public $sessionName="captcha";
	/**
	 * 调试
	 * 
	 * @var string
	 */
	public $debug=false;
	/**
	 * @var views\ViewInterface
	 */
	protected $view;
	/**
	 * @var creator\CreatorInterface
	 */
	protected $creator;
	/**
	 * @param views\ViewInterface $view
	 */
	public function setView(views\ViewInterface $view){	$this->view=$view;	}
	/**
	 * @param creator\CreatorInterface $creator
	 */
	public function setCreator(creator\CreatorInterface $creator){ $this->creator=$creator; }
	/**
	 * @return views\AdvView
	 */
	public function getView(){ return $this->view; }
	/**
	 * @return creator\CreatorInterface
	 */
	public function getCreator(){ return $this->creator; }
	/**
	 * 
	 * @see \qing\com\Component::initComponent()
	 */
	public function initComponent(){
		$this->sessionName=$this->sessionName.'_'.$this->comName;
	}
	/**
	 * 编码验证码的值
	 * 不区分大小写！strtolower
	 * 
	 * @param string $captcha
	 * @return string
	 */
	protected function encode($captcha){
		return md5($this->sessionName.'_'.strtolower($captcha));
	}
	/**
	 * 持久化验证码的值，保存到session
	 * 保存验证码字符串;小写的md5字串
	 * 
	 * @param string $captcha
	 */
	public function set($captcha){
		$_SESSION[$this->sessionName]=$this->encode($captcha);
		//debug
		$this->debug && $_SESSION[$this->sessionName.'_real']=$captcha;
	}
	/**
	 * 取得验证码
	 *
	 * @return string
	 */
	public function get(){
		return $_SESSION[$this->sessionName];
	}
	/**
	 * - 更新验证码
	 * - 成功提交之后才更新验证码|错误提交不更新
	 */
	public function update(){
		$_SESSION[$this->sessionName]='';
	}
	/**
	 * 验证验证码
	 * 
	 * @param string $code
	 * @return boolean
	 */
	public function check($captcha){
		if($this->get()==$this->encode($captcha)){
			//#成功提交之后才更新验证码|避免提交多次
			$this->update();
			return true;
		}else{
			//#错误提交不更新|可以提交多次
			return false;
		}
	}
	/**
	 * 显示验证码
	 */
	public function show(){
		if(!$this->view){
			$this->view=new views\View();
		}
		if(!$this->creator){
			$this->creator=new creator\StringCreator();
		}
		list($code,$ans)=(array)$this->creator->create();
		//#保存最终答案
		$this->set($ans);
		//#显示
		$this->view->show($code);
	}
}
?>