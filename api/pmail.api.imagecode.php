<?php
/**
 * Encoding     :   UTF-8
 * Author       :   zhujiyu , zhujiyu@139.com
 * Created on   :   2012-7-20 11:17:59
 * Copyright    :   2011 社交化协同服务办公系统项目
 */
require_once '../common.inc.php';

/************************************************
//FILE:ImageCode
//DONE:生成动态验证码类
//DATE"2010-3-31
//Author:www.5dkx.com 5D开心博客
************************************************************************/
class pmImageCode extends DisObject
{
    private $width; //验证码图片宽度
    private $height; //验证码图片高度
    private $codeNum; //验证码字符个数
    private $checkCode; //验证码字符
    private $image; //验证码画布

    function __construct($codeNum, $width = 100, $height = 40)
    {
        $this->codeNum = $codeNum;
        $this->width = $width;
        $this->height = $height;
        $this->checkCode = $this->randImageCode();
    }

    function showImage()
    {
        $this->createImage();
        $this->disturbColor();
        $this->outputText();
        $this->outputImage();
    }

    function getCheckCode()
    {
        return $this->checkCode;
    }

    private function createImage()
    {
        $this->image = imagecreatetruecolor($this->width, $this->height);
        $back = imagecolorallocate($this->image, 235, 235, 235);
        imagefilledrectangle($this->image, 0, 0, $this->width - 1, $this->height - 1, $back);
//        $border = imagecolorallocate($this->image, 0, 0, 0);
//        imagefill($this->image, 0, 0, $back);
//        imagerectangle($this->image, 0, 0, $this->width - 1, $this->height - 1, $border);
    }

    private function randImageCode()
    {
        $chars = "23456789ABCDEFGHJKMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz";
        for ( $i = 0, $asc = ""; $i < $this->codeNum; $i ++ )
        {
            $asc .= $chars[rand(0, 53)];
        }
        return $asc;
    }

    // 干扰吗设置
    private function disturbColor()
    {
        for( $i = 0; $i <= 100; $i ++ )
        {
            $color = imagecolorallocate($this->image,rand(0,255),rand(0,255),rand(0,255));
            imagesetpixel($this->image, rand(1,$this->width-2), rand(1,$this->height-2), $color);
        }
        $color = imagecolorallocate($this->image,0,0,0);
        imagesetpixel($this->image,rand(1,$this->width-2),rand(1,$this->height-2),$color);
    }

    private function outputText()
    {
        //随机颜色、随机摆放、随机字符串向图像输出
        for( $i = 0; $i <= $this->codeNum; $i ++ )
        {
            $bg_color = imagecolorallocate($this->image, rand(0,255), rand(0,255), rand(0,255));
            $x = floor($this->width/$this->codeNum) * $i + 3;
            $y = rand(0, $this->height - 15);
            imagechar($this->image, 5, $x, $y, $this->checkCode[$i], $bg_color);
        }
    }

    private function outputImage()
    {
        header("Content-type:image/jpeg");
        imagejpeg($this->image);
    }

    function __destruct()
    {
        imagedestroy($this->image);
    }
}

try
{
    $image = new pmImageCode(4, 90, 30);
    $image->showImage();
    $_SESSION['ImageCode'] = $image->getCheckCode();
}
catch (DisException $ex)
{
    $ex->trace_stack();
}
?>