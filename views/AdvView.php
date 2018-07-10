<?php
namespace qingmvc\captcha\views;
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------
/**
 * 高级视图
 * 
 * @copyright 引用自thinkphp
 */
class AdvView extends View{
	/**
	 * 'abcdefhijkmnpqrstuvwxyz0123456789+-=?.';
	 * '0123456789+-=?.';
	 *  
	 * @var number
	 */
	public $dots='abcdefhijkmnpqrstuvwxyz0123456789+-=?.';
	/**
	 * 点状干扰
	 *
	 * @var boolean
	 */
	public $dotsOn=true;
	/**
	 * 线状干扰
	 *
	 * @var boolean
	 */
	public $linesOn=true;
	/**
	 * 更多的背景干扰图像
	 *
	 * @param mixed $image
	 */
	protected function bg_more(){
		$this->dotsOn  && $this->bg_dots();
		$this->linesOn && $this->bg_lines();
	}
	/**
	 * 点状背景干扰
	 * 许许多多的数字字母
	 * 点数量=6*8
	 */
	protected function bg_dots(){
		$len=strlen($this->dots);
		for($i=0;$i<8;$i++){
			$color=imagecolorallocate($this->_image,mt_rand(160,220), mt_rand(160,220), mt_rand(160,220));
			for($j=0;$j<6;$j++){
				//如果 font 1 2 3 4 5,则使用内置字体
				imagestring($this->_image,5,mt_rand(-6, $this->width),mt_rand(-6,$this->height),$this->dots[mt_rand(0,$len-1)], $color);
			}
		}
	}
	/**
	 * 线条背景干扰
	 * 
	 * 画一条由两条连在一起构成的随机正弦函数曲线作干扰线
	 *
	 * 正弦型函数解析式：y=Asin(ωx+φ)+b
	 *     各常数值对函数图像的影响：
	 *     A：决定峰值（即纵向拉伸压缩的倍数）
	 *     b：表示波形在Y轴的位置关系或纵向移动距离（上加下减）
	 *     φ：决定波形与X轴位置关系或横向移动距离（左加右减）
	 *     ω：决定周期（最小正周期T=2π/∣ω∣）
	 */
	protected function bg_lines(){
		$height  =$this->height;
		$width   =$this->width;
		$lineSize=(int)($this->fontSize/5);
	
		//#曲线前部分
		$px=$py=0;
		//#振幅
		$A=mt_rand(1,$height/2);
		//#Y轴方向偏移量
		$b=mt_rand(-$height/4,$height/4);
		//#X轴方向偏移量
		$f=mt_rand(-$height/4,$height/4);
		//#周期
		$T=mt_rand($height,$width*2);
		//#T=2π/∣ω∣
		$w=(2*M_PI)/$T;
	
		//曲线横坐标起始位置
		$px1=-($this->fontSize);
		//曲线横坐标结束位置
		$px2=mt_rand($width/2,$width*0.8);
		$px2=$width;
		$px2=$width*0.5;
	
		for($px=$px1;$px<=$px2;$px=$px+1){
			if($w!=0){
				//y=Asin(ωx+φ)+b
				$py  =$A*sin($w*$px+$f)+$b+$height/2;
				$size=$lineSize;
				while($size>0){
					//#循环描绘像素|线的宽度
					imagesetpixel($this->_image,$px+$size,$py+$size,$this->_textcolor);
					$size--;
				}
			}
		}
	
		//#曲线后部分
		//#振幅
		$A=mt_rand(1,$height/2);
		//#X轴方向偏移量
		$f=mt_rand(-$height/4,$height/4);
		//#周期
		$T=mt_rand($height,$width*2);
		//#T=2π/∣ω∣
		$w=(2*M_PI)/$T;
		//#Y轴方向偏移量|和前一段曲线接上走势一致
		$b=$py-$A*sin($w*$px+$f)-$height/2;
		//曲线横坐标起始位置
		$px1=$px2;
		//曲线横坐标结束位置
		$px2=$width;
	
		for($px=$px1;$px<=$px2;$px=$px+1){
			if($w!=0){
				//y=Asin(ωx+φ)+b
				$py  =$A*sin($w*$px+$f)+$b+$height/2;
				$size=$lineSize;
				while($size>0){
					imagesetpixel($this->_image,$px+$size,$py+$size,$this->_textcolor);
					$size--;
				}
			}
		}
	}
}
?>