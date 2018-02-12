<?php
/**
 * Copyright 2018 cw1997. All Rights Reserved.
 * Project: tieba-show
 * File: Image.php
 * Author: cw1997 [867597730@qq.com]
 * Website: changwei.me
 * Repo: https://github/cw1997
 * Date: 2018/2/5
 * Time: 0:35
 */

namespace TiebaShow\image;


class Image implements IImage
{
    // portrait's width
    const intWidth = 110;

    const TYPE_BMP = 1;
    const TYPE_GIF = 2;
    const TYPE_JPEG = 3;
    const TYPE_PNG = 4;

    public static function sequenceMerge($strSavePath, $intOutputType, $arrImagePath, $intXNum, $intYNum)
    {
        // TODO: Implement sequenceMerge() method.
        $intNum = $intXNum * $intYNum;
        if ($intNum > count($arrImagePath)) return null;

        /**
         * 使用imagecreatetruecolor代替imagecreate，参考https://segmentfault.com/q/1010000013154816
         */
        $resImage = imagecreatetruecolor(self::intWidth * $intXNum, self::intWidth * $intYNum);
//        $resImage = imagecreate(self::intWidth * $intXNum, self::intWidth * $intYNum);
//        foreach ($arrImagePath as $intIndex => $strImagePath) {
        for ($intIndex = 0; $intIndex < $intNum; ++$intIndex) {
            $strImagePath = $arrImagePath[$intIndex];
//            $intWidth = null;
//            $intHeight = null;
//            $type = null;
//            $attr = null;
            list($intWidth, $intHeight, $type, $attr) = getimagesize($strImagePath);

//            try
//            {
//            }
//            catch(\Exception $e)
//            {
//                echo 'Message: ' .$e->getMessage();
//            }
//            finally
//            {
//                echo $strImagePath . "<br>";
//            }
//            echo "getimagesize($strImagePath) : $intWidth, $intHeight, $type, $attr \n";
            $resSourceImage = null;
//            \exif_imagetype($strImagePath)
            switch ($type) {
                case IMAGETYPE_JPEG:
                    $resSourceImage = imagecreatefromjpeg($strImagePath);
                    break;
                case IMAGETYPE_PNG:
                    $resSourceImage = imagecreatefrompng($strImagePath);
                    break;
                case IMAGETYPE_GIF:
                    $resSourceImage = imagecreatefromgif($strImagePath);
                    break;
                default:
                    echo "unknown type: {$type} \n";
            }
            /**
             * w=4, h=3
             * + + + +
             * + + + +
             * + + - +
             * 求index=10的x和y的公式
             * x = index % w
             * y = index / h - 1
             */
            $intDstX = self::intWidth * ($intIndex % $intXNum);
            $intDstY = self::intWidth * intval($intIndex / $intXNum);
            $intSrcX = 0;
            $intSrcY = 0;
            $intDstW = self::intWidth;
            $intDstH = self::intWidth;
            $intSrcW = $intWidth;
            $intSrcH = $intHeight;
            if ($intWidth != self::intWidth || $intHeight != self::intWidth) {
//                imagecopyresized($resImage, $resSourceImage, $intDstX, $intDstY, $intSrcX, $intSrcY, $intDstW, $intDstH, $intSrcW, $intSrcH);
                imagecopyresampled($resImage, $resSourceImage, $intDstX, $intDstY, $intSrcX, $intSrcY, $intDstW, $intDstH, $intSrcW, $intSrcH);
            } else {
                imagecopy($resImage, $resSourceImage, $intDstX, $intDstY, $intSrcX, $intSrcY, $intSrcW, $intSrcH);
            }
        }

        $resOutput = null;
        switch ($intOutputType) {
            case self::TYPE_BMP:
//                $resOutput = imagebmp($resImage, $strSavePath);
                break;
            case self::TYPE_GIF:
                if (is_null($strSavePath)) header("Content-Type: image/gif");
                $resOutput = imagegif($resImage, $strSavePath);
                break;
            case self::TYPE_JPEG:
                if (is_null($strSavePath)) header("Content-Type: image/jpeg");
                $resOutput = imagejpeg($resImage, $strSavePath);
                break;
            case self::TYPE_PNG:
                if (is_null($strSavePath)) header("Content-Type: image/png");
                $resOutput = imagepng($resImage, $strSavePath);
//                $resOutput = imagepng($resImage);
                break;
        }
        return $resOutput;
    }

    public static function getSavePath($arrConfig, $arrTask, $bolIsVirtual=true) {
        $strForumName = $arrTask['forum_name'];
//        $intCreateTime = $arrTask['create_time'];
        $intOutputType = $arrTask['output_type'];
        $strForumName = iconv('UTF-8', 'GBK', $strForumName);
        $strSavePath = $arrConfig['spider']['path']['output_save_path'].'/'.$strForumName.'_'.$arrTask['width'].'_'.$arrTask['height'];
        if (!$bolIsVirtual) {
            $strSavePath = $arrConfig['spider']['path']['base_path'].'/'.$strSavePath;
        }
//        var_dump($strSavePath);
        switch ($intOutputType) {
            case self::TYPE_BMP:
//                $resOutput = imagebmp($resImage, $strSavePath);
                break;
            case self::TYPE_GIF:
                $strSavePath .= '.gif';
                break;
            case self::TYPE_JPEG:
                $strSavePath .= '.jpg';
                break;
            case self::TYPE_PNG:
                $strSavePath .= '.png';
                break;
        }
        return $strSavePath;
    }
}


