<?php
/**
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
use qingmvc\captcha;

$cap=captcha\CaptchaX::math();
$view=$cap->getView();
$view->width=120;
$cap->show();

?>