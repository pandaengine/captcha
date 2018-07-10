<?php
namespace qingmvc\captcha\views;
/**
 * 算术验证码图形版
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
class View implements ViewInterface{
	/**
	 * 验证码宽度|260
	 *
	 * @var number
	 */
	public $width=0;
	/**
	 * 验证码高度
	 *
	 * @var number
	 */
	public $height=0;
	/**
	 * 背景颜色
	 * - [243,251,254]
	 * - [255,255,255]
	 * 
	 * @var array
	 */
	public $bgColor=[255,255,255];
	/**
	 * 文本颜色
	 * - [243,251,254]
	 * - [0,0,0]
	 * 
	 * @var array
	 */
	public $textColor;
	/**
	 * 验证码字符大小
	 *
	 * @var number
	 */
	public $fontSize=25;
	/**
	 * 图像内垂直基线位置，默认为中间位置
	 *
	 * @var string
	 */
	public $baselineY=null;
	/**
	 * 图像内水平边距，左右边距分别应用$paddingX px;
	 * 注意:限定宽度时，内左边距会向右推进，但总宽度不变；
	 * 自定宽度时，宽度会增加
	 *
	 * @var number
	 */
	public $paddingX=10;
	/**
	 * 是否旋转字符
	 *
	 * @var number
	 */
	public $rotate=true;
	/**
	 * TTF字体集合
	 * 需要使用绝对路径!
	 * 
	 * ['Yahoo','13misa','Airbus']
	 * 
	 * @var array
	 */
	public $ttfs;
	/**
	 * 字符个数，中文?
	 *
	 * @var number
	 */
	protected $_charNum;
	/**
	 * 当前图像实例
	 *
	 * @var mixed
	 */
	protected $_image;
	/**
	 * 验证码字符颜色
	 *
	 * @var mixed
	 */
	protected $_textcolor;
	/**
	 * 显示验证码
	 * 
	 * @see \ViewInterface::show()
	 * @param string $chars 验证码，暂不支持中文
	 */
	public function show($chars){
		$chars=(string)$chars;
		//字符个数，中文？
		if(function_exists('iconv_strlen')){
			$this->charNum=iconv_strlen($chars);
		}else{
			$this->charNum=strlen($chars);
		}
		//初始化
		$this->init();
		//创建一个空的图像
		$image=imagecreate($this->width, $this->height);
		//验证码背景色
		$bg=imagecolorallocate($image,$this->bgColor[0],$this->bgColor[1],$this->bgColor[2]);
		//验证码字符颜色
		if($this->textColor && is_array($this->textColor)){
			$textcolor=imagecolorallocate($image,$this->textColor[0],$this->textColor[1],$this->textColor[2]);
		}else{
			$textcolor=imagecolorallocate($image, mt_rand(1,160), mt_rand(1,160), mt_rand(1,160));
		}
		$this->_textcolor=$textcolor;
		$this->_image=$image;
		//#更多的背景干扰图像
		$this->bg_more();
		
		//#绘制验证码
		//#验证码字体
		$fontFile=$this->getFontFile();
		if($this->rotate){
			//#每个字符都旋转，一个个的写入字符，每个字符旋转方向不同
			//验证码第N个字符的左边距,padding-left 内左边距
			$paddingLeft=$this->paddingX+$this->fontSize/2;
			for($i=0;$i<$this->charNum;$i++){
				$c=$chars[$i];
				imagettftext($image,$this->fontSize,mt_rand(-48, 48),$paddingLeft,$this->baselineY,$textcolor,$fontFile,$c);
				//每个相差大约一个字符大小距离
				$paddingLeft+=mt_rand($this->fontSize*1.6,$this->fontSize*2.2);
			}
		}else{
			//#同一直线旋转，一次性写入所有字符，同一直线方向，一般用于数学公式
			//整体旋转角度,deg2rad角度转弧度
			$angle=mt_rand(-12,12);
			$y=($this->width/2)*sin(deg2rad($angle))+$this->baselineY;
			imagettftext($image,$this->fontSize,$angle,$this->paddingX,$y,$textcolor,$fontFile,$chars);
		}
		//输出图片
		$this->output($image);
	}
	/**
	 * 初始化
	 */
	protected function init(){
		$this->height	=(int)$this->height;
		$this->width	=(int)$this->width;
		$this->fontSize	=(int)$this->fontSize;
	
		//验证码高度，根据字体大小自动设置宽高
		!$this->height &&  $this->height=$this->fontSize*2.3;
		//字符基线位置
		if(!$this->baselineY){
			//限定高度，基线为高度一半
			$this->baselineY=$this->height/1.6;
		}
		//验证码宽
		if(!$this->width){
			$this->width=$this->fontSize+($this->charNum-1)*$this->fontSize*1.6+$this->fontSize;
			//添加水平边距
			$this->width+=$this->paddingX;
		}
	}
	/**
	 * 更多的背景干扰图像
	 *
	 * @param mixed $image
	 */
	protected function bg_more(){}
	/**
	 * 输出图像
	 * 
	 * @see imagepng imagejpg
	 * @param mixed $image
	 */
	protected function output($image){
		header('Pragma: no-cache');
		header("Content-type: image/png");
		//输出图像
		imagepng($image);
		imagedestroy($image);
	}
	/**
	 * 取得TTF字体路径
	 * 
	 * @return string
	 */
	protected function getFontFile(){
		if(!$this->ttfs){
			//默认字体列表
			$path=__DIR__.'/../ttfs';
			$this->ttfs=[$path.'/Yahoo.ttf',$path.'/13misa.ttf',$path.'/Airbus.ttf'];
		}
		$rand=mt_rand(0,count($this->ttfs)-1);
		$file=$this->ttfs[$rand];
		if(!is_file($file)){
			throw new \qing\exceptions\NotfoundFileException($file);
		}
		return $file;
	}
}
?>