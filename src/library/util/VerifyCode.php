<?php
/**
 * Copyright 2018 cw1997. All Rights Reserved.
 * Project: tieba-show
 * File: VerifyCode.php
 * Author: cw1997 [867597730@qq.com]
 * Website: changwei.me
 * Repo: https://github/cw1997
 * Date: 2018/2/6
 * Time: 23:46
 */


namespace TiebaShow\util;


class VerifyCode implements IVerifyCode
{
    const _sign = 'cw1997-tieba-show-2018';

    public static function generate() {
//        TODO:写缓存
        $strToken = md5(self::_sign.time());
        $strCode = self::_generateCode();
        $resCache = new Redis('127.0.0.1', 6379);
        $resCache->store($strToken, $strCode, 300);
        return $strToken;
    }

    public static function check($strToken, $strInputCode)
    {
        $resCache = new Redis('127.0.0.1', 6379);
        $strCode = $resCache->load($strToken);
        return $strInputCode == $strCode;
    }

    public static function showImage($strValue, $bolIsToken=true)
    {
        if ($bolIsToken) {
            $resCache = new Redis('127.0.0.1', 6379);
            $strCode = $resCache->load($strValue);
//            var_dump($strCode);
            self::_generateImage($strCode);
        } else {
            self::_generateImage($strValue);
        }
    }

    private static function _generateCode($data='abcdefghijkmnpqrstuvwxy3456789') {
        $strCode = '';
        for ($i=0; $i<4; ++$i) {
            $chrCode = substr($data, rand(0, strlen($data)-1), 1);
            $strCode .= $chrCode;
        }
        return $strCode;
    }

/*    private static function _generateImage($strCode)
    {
        $image = imagecreatetruecolor(100, 30);
        $bgcolor = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $bgcolor);

        for($i=0;$i<4;$i++) {
            $fontsize = 6;
            $fontcolor = imagecolorallocate($image, rand(0, 120), rand(0, 120),rand(0, 120));

            $x = ($i*100/4) + rand(5, 10);
            $y = rand(5, 10);

            imagestring($image, $fontsize, $x, $y, $strCode, $fontcolor);
        }

        //增加点干扰元素
        for($i=0; $i<200;$i++) {
            $pointcolor = imagecolorallocate($image, rand(50,200), rand(50,200), rand(50,200));
            imagesetpixel($image, rand(1,99), rand(1,29), $pointcolor);
        }

        //增加线干扰元素
        for($i=0;$i<3;$i++) {
            $linecolor = imagecolorallocate($image, rand(80,220), rand(80,220), rand(80, 220));
            imageline($image, rand(1,99), rand(1,29), rand(1,99), rand(1,29), $linecolor);
        }


        header('content-type:image/png');
        imagepng($image);

//        imagedestroy($image);
//        return $image;
    }*/

    private static function _generateImage($strCode)
    {
        /**
         * 字母+数字的验证码生成
         */
// 开启session
//        session_start();
//1.创建黑色画布
        $image = imagecreatetruecolor(100, 30);

//2.为画布定义(背景)颜色
        $bgcolor = imagecolorallocate($image, 255, 255, 255);

//3.填充颜色
        imagefill($image, 0, 0, $bgcolor);

// 4.设置验证码内容

//4.1 定义验证码的内容
//        $content = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

//4.1 创建一个变量存储产生的验证码数据，便于用户提交核对
//        $captcha = $strCode;
        for ($i = 0; $i < 4; $i++) {
            // 字体大小
            $fontsize = 10;
            // 字体颜色
            $fontcolor = imagecolorallocate($image, mt_rand(0, 120), mt_rand(0, 120), mt_rand(0, 120));
            // 设置字体内容
//            $fontcontent = substr($content, mt_rand(0, strlen($content)), 1);
//            $captcha .= $fontcontent;
            // 显示的坐标
            $x = ($i * 100 / 4) + mt_rand(5, 10);
            $y = mt_rand(5, 10);
            // 填充内容到画布中
            imagestring($image, $fontsize, $x, $y, $strCode{$i}, $fontcolor);
        }
//        $_SESSION["captcha"] = $captcha;

//4.3 设置背景干扰元素
        for ($i = 0; $i < 200; $i++) {
            $pointcolor = imagecolorallocate($image, mt_rand(50, 200), mt_rand(50, 200), mt_rand(50, 200));
            imagesetpixel($image, mt_rand(1, 99), mt_rand(1, 29), $pointcolor);
        }

//4.4 设置干扰线
        for ($i = 0; $i < 3; $i++) {
            $linecolor = imagecolorallocate($image, mt_rand(50, 200), mt_rand(50, 200), mt_rand(50, 200));
            imageline($image, mt_rand(1, 99), mt_rand(1, 29), mt_rand(1, 99), mt_rand(1, 29), $linecolor);
        }

//5.向浏览器输出图片头信息
        header('content-type:image/png');

//6.输出图片到浏览器
        imagepng($image);

//7.销毁图片
        imagedestroy($image);
    }
}