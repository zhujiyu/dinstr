<?php
/**
 * @package: DIS.PLUGIN
 * @file   : DisImagePlg.class.php
 * @abstract  : 图片处理
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 DIS 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisImagePlg
{
    var $PICTURE_URL;       //要处理的图片
    var $DEST_URL = "temp__01.jpg"; //生成目标图片位置
    var $PICTURE_CREATE;    //要创建的图片
    var $TURE_COLOR;        //新建一个真彩图象

    var $PICTURE_WIDTH; //原图片宽度
    var $PICTURE_HEIGHT; //原图片高度

    var $PICTURE_TYPE;  //图片类型
    var $PICTURE_MIME;  //输出的头部

/*
缩放比例为1的话就按缩放高度和宽度缩放
*/
    var $ZOOM = 1;//缩放类型
    var $ZOOM_MULTIPLE;//缩放比例
    var $ZOOM_WIDTH;//缩放宽度
    var $ZOOM_HEIGHT;//缩放高度

/*
裁切，按比例和固定长度、宽度
*/
    var $CUT_TYPE = 1;//裁切类型
    var $CUT_X = 0;//裁切的横坐标
    var $CUT_Y = 0;//裁切的纵坐标
    var $CUT_WIDTH = 100;//裁切的宽度
    var $CUT_HEIGHT = 100;//裁切的高度

    /*
     水印的类型，默认的为水印文字
     */
    var $MARK_TYPE = 1;
    var $WORD;          //经过UTF-8后的文字
    var $WORD_X;        //文字横坐标
    var $WORD_Y;        //文字纵坐标
    var $FONT_TYPE;     //字体类型
    var $FONT_SIZE = "14";          //字体大小
    var $FONT_WORD = "天鹅镇";     //文字
    var $ANGLE = 0;     //文字的角度，默认为0
    var $FONT_COLOR = "#105cb6";    //文字颜色
    var $FONT_PATH = "22.ttf";      //字体库，默认为宋体
    var $RED;
    var $GREEN;
    var $BLUE;

    var $FORCE_URL;         //水印图片
    var $FORCE_X = 0;       //水印横坐标
    var $FORCE_Y = 0;       //水印纵坐标
    var $FORCE_START_X = 0; //切起水印的图片横坐标
    var $FORCE_START_Y = 0; //切起水印的图片纵坐标

    /*
    出错信息
    */
    var $ERROR = array('unalviable'=>'没有找到相关图片!');

/**
 * 构造函数：函数初始化
 */
function __construct($PICTURE_URL)
{
    $this->get_info($PICTURE_URL);
}

/**
处理原图片的信息,先检测图片是否存在,不存在则给出相应的信息
*/
function get_info($PICTURE_URL)
{
    $SIZE = getimagesize($PICTURE_URL);
    if( !$SIZE )
    {
       exit($this->ERROR['unalviable']);
    }

    //得到原图片的信息类型、宽度、高度
    $this->PICTURE_MIME   = $SIZE['mime'];
    $this->PICTURE_WIDTH  = $SIZE[0];
    $this->PICTURE_HEIGHT = $SIZE[1];

    //创建图片
    switch($SIZE[2])
    {
       case 1:
        $this->PICTURE_CREATE = imagecreatefromgif($PICTURE_URL);
        $this->PICTURE_TYPE   = "imagegif";
        $this->PICTURE_EXT    = "gif";
        break;
       case 2:
        $this->PICTURE_CREATE = imagecreatefromjpeg($PICTURE_URL);
        $this->PICTURE_TYPE   = "imagejpeg";
        $this->PICTURE_EXT    = "jpg";
        break;
       case 3:
        $this->PICTURE_CREATE = imagecreatefrompng($PICTURE_URL);
        $this->PICTURE_TYPE   = "imagepng";
        $this->PICTURE_EXT    = "png";
        break;
    }
}
#end of __construct

/**
 * 将16进制的颜色转换成10进制的（R，G，B）
 */
function hex2dec($color)
{
    $MATCHES = array();
    if( !preg_match_all("/([0-f]{2,2})/i", $color, $MATCHES) )
        return;
//    preg_match_all("/([0-f]{2,2})/i", $color, $MATCHES);

    if( count($MATCHES[0]) == 3 )
    {
       $this->RED   = hexdec($MATCHES[0][0]);
       $this->GREEN = hexdec($MATCHES[0][1]);
       $this->BLUE  = hexdec($MATCHES[0][2]);
    }
    else
    {
        exit('错误的颜色格式');
    }
}

/** 对图片进行缩放,如果不指定高度和宽度就进行缩放 */
function zoom()
{
    //缩放的大小
    if( $this->ZOOM == 0 )
    {
       $this->ZOOM_WIDTH  = floor( $this->PICTURE_WIDTH * $this->ZOOM_MULTIPLE );
       $this->ZOOM_HEIGHT = floor( $this->PICTURE_HEIGHT * $this->ZOOM_MULTIPLE);
    }

    //新建一个真彩图象
    $this->TRUE_COLOR = imagecreatetruecolor($this->ZOOM_WIDTH, $this->ZOOM_HEIGHT);
    $WHITE = imagecolorallocate($this->TRUE_COLOR, 255, 255, 255);
    imagefilledrectangle($this->TRUE_COLOR, 0, 0, $this->ZOOM_WIDTH, $this->ZOOM_HEIGHT, $WHITE);
    imagecopyresized($this->TRUE_COLOR, $this->PICTURE_CREATE, 0, 0, 0, 0, $this->ZOOM_WIDTH, $this->ZOOM_HEIGHT, $this->PICTURE_WIDTH, $this->PICTURE_HEIGHT);
}
#end of zoom

/**
 * 裁切图片,按坐标或自动
 */
function cut($srcRect = null)
{
    if( $srcRect == null )
        $srcRect = array('left'=>0, 'top'=>0, 'width'=>$this->PICTURE_WIDTH, 'height'=>$this->PICTURE_HEIGHT);
    $this->TRUE_COLOR = imagecreatetruecolor($this->CUT_WIDTH, $this->CUT_HEIGHT);

    $this->CUT_X = $srcRect['left'];
    $this->CUT_Y = $srcRect['top'];
    $w = $srcRect['width'];
    $h = $srcRect['height'];

    if( min( $w, $h, $this->CUT_WIDTH, $this->CUT_HEIGHT) == 0 )
        exit('裁剪尺寸为零，或者获取图片尺寸');

    $bl = $this->CUT_WIDTH / $this->CUT_HEIGHT;
    $bl1 = $w / $h;

    if( $this->CUT_TYPE == 1 )
    {
        if( $srcRect['width'] > $srcRect['height'] )
            $this->CUT_X += floor( ($srcRect['width'] - $srcRect['height']) / 2 );
        else
            $this->CUT_Y += floor( ($srcRect['height'] - $srcRect['width']) / 2 );

        if( $bl > $bl1 )
            $h = floor( $w * $bl );
        elseif( $bl < $bl1 )
            $w = floor( $h * $bl );

        imagecopyresampled($this->TRUE_COLOR, $this->PICTURE_CREATE, 0, 0, $this->CUT_X, $this->CUT_Y, $this->CUT_WIDTH, $this->CUT_HEIGHT, $w, $h);
    }
    else
    {
        if( $bl > $bl1 )
        {
            $h = floor( $w * $bl );
        }
        elseif( $bl < $bl1 )
        {
            $w = floor( $h * $bl );
        }
        imagecopyresampled($this->TRUE_COLOR, $this->PICTURE_CREATE, 0, 0, $this->CUT_X, $this->CUT_Y, $this->CUT_WIDTH, $this->CUT_HEIGHT, $w, $h);
    }
}
#end of cut

function mark_domain()
{
    /*        将背景图拷贝到画布中    */
    $this->TRUE_COLOR = imagecreatetruecolor($this->PICTURE_WIDTH, $this->PICTURE_HEIGHT);
    imagecopy($this->TRUE_COLOR, $this->PICTURE_CREATE, 0, 0, 0, 0, $this->PICTURE_WIDTH, $this->PICTURE_HEIGHT);

    $this->hex2dec($this->FONT_COLOR);
    $color = imagecolorallocate($this->TRUE_COLOR, $this->RED, $this->GREEN, $this->BLUE); // #105cb6
    $y = max(0, $this->PICTURE_HEIGHT - 25);

    $domain = "tianezhen.com";
    $num = strlen($domain);
    //随机颜色、随机摆放、随机字符串向图像输出
    for ( $i = 0; $i < $num; $i ++ )
    {
        $x = max(0, $this->PICTURE_WIDTH - 150 + $i * 10);
        imagechar($this->TRUE_COLOR, 5, $x, $y, $domain[$i], $color);
    }
}

/**
 * 在图片上放文字或图片 水印文字
 */
function _mark_text()
{
//    $this->TRUE_COLOR = imagecreate($this->PICTURE_WIDTH, $this->PICTURE_HEIGHT);
//    $WHITE = imagecolorallocate($this->TRUE_COLOR, 255, 205, 205);
//    imagefilledrectangle($this->TRUE_COLOR, 0, 0, $this->PICTURE_WIDTH-1, $this->PICTURE_HEIGHT-1, $WHITE);

    /*        将背景图拷贝到画布中    */
    $this->TRUE_COLOR = imagecreatetruecolor($this->PICTURE_WIDTH, $this->PICTURE_HEIGHT);
    imagecopy($this->TRUE_COLOR, $this->PICTURE_CREATE, 0, 0, 0, 0, $this->PICTURE_WIDTH, $this->PICTURE_HEIGHT);

//    $this->WORD = mb_convert_encoding($this->FONT_WORD, 'utf-8', 'gb2312');
//    $this->WORD = iconv('gb2312', 'utf-8', $this->FONT_WORD);
    $this->WORD = $this->FONT_WORD;
    $this->WORD = "iloveyou";
    $this->FONT_PATH = "font/arial.ttf";

    /*    取得使用 TrueType 字体的文本的范围    */
//    $TEMP = imagettfbbox($this->FONT_SIZE, 0, $this->FONT_PATH, $this->WORD);
//    $WORD_LENGTH = strlen($this->WORD);
//    $WORD_WIDTH  = $TEMP[2] - $TEMP[6];
//    $WORD_HEIGHT = $TEMP[3] - $TEMP[7];
    $WORD_WIDTH  = 400;
    $WORD_HEIGHT = 400;

    /*    文字水印的默认位置为右下角    */
    if ( $this->WORD_X == "" )
    {
        $this->WORD_X = $this->PICTURE_WIDTH - $WORD_WIDTH;
    }
    if( $this->WORD_Y == "" )
    {
        $this->WORD_Y = $this->PICTURE_HEIGHT - $WORD_HEIGHT;
    }
    $this->WORD_X = 0;
    $this->WORD_Y = 0;

    $this->hex2dec($this->FONT_COLOR);
    $color = imagecolorallocate($this->TRUE_COLOR, $this->RED, $this->GREEN, $this->BLUE);
    imagettftext($this->TRUE_COLOR, $this->FONT_SIZE, $this->ANGLE, $this->WORD_X, $this->WORD_Y, $color, $this->FONT_PATH, $this->WORD);
}

/** 水印图片 */
function _mark_picture()
{
    /*    获取水印图片的信息    */
    @$SIZE = getimagesize($this->FORCE_URL);
    if( !$SIZE )
    {
        exit($this->ERROR['unalviable']);
    }
    $FORCE_PICTURE_WIDTH  = $SIZE[0];
    $FORCE_PICTURE_HEIGHT = $SIZE[1];

    //创建水印图片
    switch($SIZE[2])
    {
       case 1:
        $FORCE_PICTURE_CREATE   = imagecreatefromgif($this->FORCE_URL);
        $FORCE_PICTURE_TYPE     = "gif";
        break;
       case 2:
        $FORCE_PICTURE_CREATE   = imagecreatefromjpeg($this->FORCE_URL);
        $FORCE_PICTURE_TYPE     = "jpg";
        break;
       case 3:
        $FORCE_PICTURE_CREATE   = imagecreatefrompng($this->FORCE_URL);
        $FORCE_PICTURE_TYPE     = "png";
        break;
    }

    /*    判断水印图片的大小，并生成目标图片的大小，如果水印比图片大，则生成图片大小为水印图片的大小。否则生成的图片大小为原图片大小。    */
    $this->NEW_PICTURE = $this->PICTURE_CREATE;
    if( $FORCE_PICTURE_WIDTH > $this->PICTURE_WIDTH )
    {
        $CREATE_WIDTH = $FORCE_PICTURE_WIDTH-$this->FORCE_START_X;
    }
    else
    {
        $CREATE_WIDTH = $this->PICTURE_WIDTH;
    }

    if( $FORCE_PICTURE_HEIGHT > $this->PICTURE_HEIGHT )
    {
        $CREATE_HEIGHT = $FORCE_PICTURE_HEIGHT-$this->FORCE_START_Y;
    }
    else
    {
        $CREATE_HEIGHT = $this->PICTURE_HEIGHT;
    }

    /*  创建一个画布 */
    $NEW_PICTURE_CREATE = imagecreatetruecolor($CREATE_WIDTH, $CREATE_HEIGHT);
    $WHITE = imagecolorallocate($NEW_PICTURE_CREATE, 255, 255, 255);

    /*        将背景图拷贝到画布中    */
    imagecopy($NEW_PICTURE_CREATE, $this->PICTURE_CREATE, 0, 0, 0, 0, $this->PICTURE_WIDTH, $this->PICTURE_HEIGHT);
    /*        将目标图片拷贝到背景图片上    */
    imagecopy($NEW_PICTURE_CREATE, $FORCE_PICTURE_CREATE, $this->FORCE_X, $this->FORCE_Y, $this->FORCE_START_X, $this->FORCE_START_Y, $FORCE_PICTURE_WIDTH, $FORCE_PICTURE_HEIGHT);
    $this->TRUE_COLOR = $NEW_PICTURE_CREATE;
}
#end of mark

/**
 * 生成目标图片并保存
 * @param integer $showpic
 */
function save_picture($showpic = 0)
{
    // 以 JPEG 格式将图像输出到浏览器或文件
    $OUT = $this->PICTURE_TYPE;
    if( !function_exists($OUT) )
        return ;

    $this->TRUE_COLOR = $this->TRUE_COLOR ? $this->TRUE_COLOR : $this->PICTURE_CREATE;
    $OUT( $this->TRUE_COLOR, $this->DEST_URL);
    if( $showpic )
        $this->show();
}

/* 生成目标图片并显示 */
function show()
{
    $OUT = $this->PICTURE_TYPE;
    if( !function_exists($OUT) )
        return ;

    // 判断浏览器,若是IE就不发送头
    if( isset($_SERVER['HTTP_USER_AGENT']) )
    {
        $ua = strtoupper($_SERVER['HTTP_USER_AGENT']);
        if( !preg_match('/^.*MSIE.*\)$/i',$ua) )
        {
            header("Content-type: $this->PICTURE_MIME");
        }
    }

    if( $this->TRUE_COLOR )
        $OUT($this->TRUE_COLOR);
    else
        $OUT($this->PICTURE_CREATE);
}

/* 析构函数：释放图片 */
function __destruct()
{
    /*释放图片*/
    @imagedestroy($this->TRUE_COLOR);
    @imagedestroy($this->PICTURE_CREATE);
}
#end of class
}
?>
