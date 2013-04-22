<?php
/**
 * @package: DIS.CTRL
 * @file   : DisPhotoCtrl.class.php
 * @abstract  :
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisPhotoCtrl extends DisPhotoData
{
    var $name;
    var $error;

    // 构造函数
    function __construct($id = null)
    {
        parent::__construct($id);
    }

    static function get_data($photo_id)
    {
//        pmRowMemcached::set_photo_data($photo_id, null);
        $photo = DisRowCache::get_photo_data($photo_id);
        if( !$photo )
        {
            $ph = new DisPhotoCtrl((int)$photo_id);
            if( $ph->ID )
            {
                $photo = $ph->info();
                if( $photo['user_id'] )
                    $photo['user'] = DisUserCtrl::get_data($photo['user_id']);
            }
            else
            {
                $photo = array('ID'=>'0', 'small'=>'', 'big'=>'', 'desc'=>"该图片已被删除！");
            }
            DisRowCache::set_photo_data($photo_id, $photo);
        }
        return $photo;
    }

    function check_error($err)
    {
        $error = '';

        switch($err)
        {
            case '1':
                $error = 'The uploaded file exceeds the upload_max_filesize
                        directive in php.ini';
                break;
            case '2':
                $error = 'The uploaded file exceeds the MAX_FILE_SIZE directive
                        that was specified in the HTML form';
                break;
            case '3':
                $error = 'The uploaded file was only partially uploaded';
                break;
            case '4':
                $error = 'No file was uploaded';
                break;
            case '6':
                $error = 'Missing a temporary folder';
                break;
            case '7':
                $error = 'Failed to write file to disk';
                break;
            case '8':
                $error = 'File upload stopped by extension';
                break;
            case '999':
            default:
                $error = 'No error code avaiable';
        }
        return $error;
    }

    static function delete_img($url, $base_path = '.')
    {
        if( !$url || $url == '' )
            throw new DisException('输入路径不正确。');
        if( file_exists("$base_path/$url") )
            unlink("$base_path/$url");
    }

    function remove($path = '.', PDO $pdo = null)
    {
        self::delete_img($this->detail['big'], $path);
        self::delete_img($this->detail['small'], $path);
        parent::delete($pdo);
    }

    protected function _generate_name($url)
    {
        $FileID = floor(time() / 60).rand(10, 99);
        $this->name = substr($FileID, 2).basename($url);

        if( strlen($this->name) >= 253 )
            throw new DisException("文件名不能超过245字节！");
        if( !eregi("(jpe?g|png|gif)$", $this->name) )
            throw new DisException("文件格式不正确！请上传 jpeg/jpg/png/gif 类型的图片");
    }

    function cut($dst_size, $photo = null, $srcRect = null)
    {
        if( !$photo )
            $photo = $this->image;
        if( $srcRect == null )
            $srcRect = array('left'=>0, 'top'=>0, 'width'=>$photo->PICTURE_WIDTH, 'height'=>$photo->PICTURE_HEIGHT);

        $photo->CUT_TYPE = 1; //裁切类型
        $photo->CUT_WIDTH  = min($dst_size, $srcRect['width'] );
        $photo->CUT_HEIGHT = min($dst_size, $srcRect['height']);
        $photo->cut($srcRect);
//        $photo->mark_domain($photo->TURE_COLOR, $photo->CUT_WIDTH, $photo->CUT_HEIGHT);
    }

    function zoom_width($WIDTH = 500, $photo = null)
    {
        if( !$photo )
            $photo = $this->image;
        if( $WIDTH > $photo->PICTURE_WIDTH )
            return;

        $photo->ZOOM_WIDTH  = $WIDTH;
        $photo->ZOOM_HEIGHT = floor( $WIDTH * $photo->PICTURE_HEIGHT / $photo->PICTURE_WIDTH );
        $photo->zoom();
//        $photo->mark_domain($photo->TURE_COLOR, $photo->ZOOM_WIDTH, $photo->ZOOM_HEIGHT);
    }

    private function _mkdir($paths, $base_path)
    {
        if( !is_array($paths) )
            throw new DisParamException("参数格式错误");

        $path = $base_path."/attach";
        $len = count($paths);

        for( $i = 0; $i < $len; $i ++ )
        {
            $path .= "/".$paths[$i];
            if( !file_exists($path) )
                @mkdir($path);
        }
    }

    protected function mark($path)
    {
        $photo = new DisImagePlg($path);
        $photo->DEST_URL = $path;
        $photo->mark_domain();
        $photo->save_picture();
    }

    function save($photo, $user_id, $base_path = ".")
    {
        $_dir = strtoupper(substr(md5($this->name), 0, 2));
        $this->name = substr(md5($this->name), 2);

        $_sml = "attach/nh100/$_dir/".$this->name;
        $photo->DEST_URL = "$base_path/$_sml";
        $this->_mkdir(array("nh100", $_dir), $base_path);
        $this->cut(100, $photo);
        $photo->save_picture();

        $_big = "attach/xw500/$_dir/".$this->name;
        $photo->DEST_URL = "$base_path/$_big";
        $this->_mkdir(array("xw500", $_dir), $base_path);
        $this->zoom_width(500, $photo);
        $photo->save_picture();

        $this->mark("$base_path/$_big");
        return $this->insert($_big, $_sml, $user_id);
    }

    function upload($upload, $user_id, $base_path = '.')
    {
        if( !empty($upload['error']) )
        {
            $error = $this->check_error($upload['error']);
            throw new DisException($error);
        }
        else if( empty($upload['tmp_name']) || $upload['tmp_name'] == 'none' )
        {
            $error = 'No file was uploaded..'.print_r($upload, true);
            throw new DisException($error);
        }
        else
        {
            $this->_generate_name($upload['name'], $base_path);
            $photo = new DisImagePlg($upload['tmp_name']);
            $this->save($photo, $user_id, $base_path);
            @unlink($upload);

//            $this->_generate_name($upload['name'], $base_path);
//            $_dir = strtoupper(substr(md5($this->name), 0, 2));
//            $this->name = substr(md5($this->name), 2);
//            $photo = new pmImage($upload['tmp_name']);
//
//            $_sml = "attach/nh100/$_dir/".$this->name;
//            $photo->DEST_URL = "$base_path/$_sml";
//            $this->_mkdir(array("nh100", $_dir), $base_path);
//            $this->cut(100, $photo);
//            $photo->save_picture();
//
//            $_big = "attach/xw500/$_dir/".$this->name;
//            $photo->DEST_URL = "$base_path/$_big";
//            $this->_mkdir(array("xw500", $_dir), $base_path);
//            $this->zoom_width(500, $photo);
//            $photo->save_picture();
//
//            $this->mark("$base_path/$_big");
//            $this->insert($_big, $_sml, $user_id);
//            @unlink($upload);
        }
    }

    function to_avatar($src, $base_path = '.')
    {
        $_url = $this->attr('big');
        $this->name = basename($_url);
        $_dir = strtoupper(substr(md5($this->name), 0, 2));
        $photo = new DisImagePlg("$base_path/$_url");

        $_big = "attach/nh200/$_dir/".$this->name;
        $photo->DEST_URL = "$base_path/$_big";
        $this->_mkdir(array("nh200", $_dir), $base_path);
        $this->cut(200, $photo, $src);
        $photo->save_picture();

        $_sml = "attach/nh50/$_dir/".$this->name;
        $photo->DEST_URL = "$base_path/$_sml";
        $this->_mkdir(array("nh50", $_dir), $base_path);
        $this->cut(50, $photo, $src);
        $photo->save_picture();

        return $this->update(array('small'=>$_sml, 'big'=>$_big));
    }

    function move($user_id, $url, $base_path = ".")
    {
        $this->_generate_name($url, $base_path);
        $photo = new DisImagePlg($url);
        return $this->save($photo, $user_id, $base_path);

//        $this->_generate_name($url, $base_path);
//        $_dir = strtoupper(substr(md5($this->name), 0, 2));
//        $this->name = substr(md5($this->name), 2);
//        $photo = new pmImage($url);
//
//        $_sml = "attach/nh100/$_dir/".$this->name;
//        $photo->DEST_URL = "$base_path/$_sml";
//        $this->_mkdir(array("nh100", $_dir), $base_path);
//        $this->cut(100, $photo);
//        $photo->save_picture();
//
//        return $this->insert($url, $_sml, $user_id);
    }

    function reduce($param, $step = 1)
    {
        parent::reduce($param, $step);
        $info = $this->info();
        $info['user'] = DisUserCtrl::get_data($this->attr('user_id'));
        DisRowCache::set_photo_data($this->ID, $info);
    }

    function increase($param, $step = 1)
    {
        parent::increase($param, $step);
        $info = $this->info();
        $info['user'] = DisUserCtrl::get_data($this->attr('user_id'));
        DisRowCache::set_photo_data($this->ID, $info);
    }

    /**
     * 保存信息所含有的关键词
     * @param integer $photo_id 图片ID
     * @param string $content 信息内容
     * @return integer 插入关键词的个数
     */
    function insert_tags($content)
    {
        $rsg = '/#([\w\x{4e00}-\x{9fa5}]+)#/ui';
        $matches = array();
        if( preg_match_all($rsg, $content, $matches) )
            $tags = $matches[1];
        else
            return;

        $len = count($tags);
        for( $i = 0; $i < $len; $i ++ )
            DisPhotoTagData::insert($this->ID, $tags[$i]);
    }

//    function remove($path = '.', PDO $pdo = null)
//    {
//        $big = $this->detail['big'];
//        $sml = $this->detail['small'];
//        @unlink("$path/$big");
//        @unlink("$path/$sml");
//        parent::delete($pdo);
//    }

//    function _upload($upload, $user_id, $base_path = '.')
//    {
//            $this->_generate_name($upload['name'], $base_path);
//
//            $up_dir = "attach/tmp";
//            if( !file_exists("$base_path/$up_dir") )
//                @mkdir("$base_path/$up_dir");
//            $temp_url = "$up_dir/$this->name";
//
//            if( !move_uploaded_file($upload['tmp_name'], "$base_path/$temp_url") )
//                throw new pmException("文件上传失败！");
//            @unlink($upload);
//
//            $photo = new pmImage("$base_path/$temp_url");
//            $_dir = strtoupper(substr(md5($this->name), 0, 2));
//            $this->name = substr(md5($this->name), 2);
//
//            $this->cut(100, $photo);
//            $this->_mkdir(array("nh100", $_dir), $base_path);
//            $_sml = "attach/nh100/$_dir/".$this->name;
//            $photo->DEST_URL = "$base_path/$_sml";
//            $photo->save_picture();
//
//            $this->zoom_width(500, $photo);
//            $this->_mkdir(array("xw500", $_dir), $base_path);
//            $_big = "attach/xw500/$_dir/".$this->name;
//            $photo->DEST_URL = "$base_path/$_big";
//            $photo->save_picture();
//
//            $this->mark("$base_path/$_big");
//            $this->insert($_big, $_sml, $user_id);
//            self::delete_img($temp_url, $base_path);
//
////             $this->_load_temp($upload, $path);
////             $this->save($user_id, $path);
////             $this->delete_temp($path);
//    }

//    protected function photo_path($dirs, $base_path = '.')
//    {
//        $img = "attach/";
//        $len = count($dirs);
//
//        for( $i = 0; $i < $len; $i ++ )
//        {
//            $img .= $dirs[$i]."/";
//        }
//
//        $img .= $this->name;
//        $this->_mkdir($dirs, $base_path);
//        return $img;
//    }

//    function to_avatar($src, $base_path = '.')
//    {
//        $this->temp_url = $this->attr('big');
//        $matches = array();
//        preg_match('/\/([^\/]+\.[a-z]+)[^\/]*$/', $this->temp_url, $matches);
//        $this->name = $matches[1];
//
//        $_dir = strtoupper(substr(md5($this->name), 0, 2));
//        $photo = new pmImage("$base_path/$this->temp_url");
//
//        $pathb = "attach/nh200";
//        $this->_mkdir($base_path, $pathb, $_dir);
//        $_big = "$pathb/$_dir/$this->name";
//
//        $this->cut(200, $photo, $src);
//        $photo->DEST_URL = "$base_path/$_big";
//        $photo->save_picture();
//
//        $paths = 'attach/nh50';
//        $this->_mkdir($base_path, $paths, $_dir);
//        $_sml = "$paths/$_dir/$this->name";
//
//        $this->cut(50, $photo, $src);
//        $photo->DEST_URL = "$base_path/$_sml";
//        $photo->save_picture();
//
//        $this->update(array('small'=>$_sml, 'big'=>$_big));
//        return $this;
//    }

//    function move($user_id, $url, $base_path = ".")
//    {
//        $FileID = floor(time() / 60).rand(10, 99);
//        $this->name = substr($FileID, 2).basename($url);
//
//        if( strlen($this->name) >= 253 )
//            throw new pmException("文件名不能超过245字节！");
//        if( !eregi("(jpe?g|png|gif)$", $this->name) )
//            throw new pmException("文件格式不正确！请上传 jpeg/jpg/png/gif 类型的图片");
//
//        $_dir = strtoupper(substr(md5($this->name), 0, 2));
//        $photo = new pmImage($url);
//        $this->save_small($photo, $_dir, $base);
//
//        $this->cut(100, $photo);
//        $photo->mark_domain();
//        $photo->DEST_URL = "$base/attach/web.".$photo->PICTURE_EXT;
//        $photo->DEST_URL = "$base/attach/web.".$photo->PICTURE_EXT;
//        $photo->save_picture();
//        $this->name = $photo->DEST_URL;
//    }

//    private function _mkdir($base_path, $path, $_dir)
//    {
//        $path = $base_path."/attach/".$path;
//        if( !file_exists($path) )
//            @mkdir("$base_path/$path");
//        if( !file_exists("$base_path/$path") )
//            @mkdir("$base_path/$path");
//        if( !file_exists("$base_path/$path/$_dir") )
//            @mkdir("$base_path/$path/$_dir");
//    }

//    protected function save_100($photo, $_dir, $base_path = '.')
//    {
//        $_sml = $this->photo_path(array("attach", "nh100", $_dir), $base_path);
//        $photo->DEST_URL = "$base_path/$_sml";
//        $this->cut(100, $photo);
//        $photo->save_picture();
//        return $_sml;
//    }

//    protected function save_small($photo, $_dir, $base_path = '.')
//    {
//        $path = "attach/nh100";
//        $this->_mkdir($base_path, $path, $_dir);
//        $_sml = "$path/$_dir/$this->name";
//
//        $this->cut(100, $photo);
//        $photo->DEST_URL = "$base_path/$_sml";
//        $photo->save_picture();
//        return $_sml;
//    }
//
//    protected function save_big($photo, $_dir, $base_path = '.')
//    {
//        $path = 'attach/xw500';
//        $this->_mkdir($base_path, $path, $_dir);
//        $_big = "$path/$_dir/$this->name";
//
//        $this->zoom_width(500, $photo);
//        $photo->DEST_URL = "$base_path/$_big";
//        $photo->save_picture();
//        return $_big;
//    }

//    function delete_temp($base_path = '.')
//    {
//        if( !$this->temp_url || $this->temp_url == '' )
//            throw new pmException('输入路径不正确。');
//        if( file_exists($this->temp_url) )
//            unlink("$base_path/$this->temp_url");
//    }
//
//    protected function _load_temp($upload, $path)
//    {
//        $FileID = floor(time() / 60).rand(10, 99);
//        $this->name = substr($FileID, 2).basename($upload['name']);
//
//        if( strlen($this->name) >= 253 )
//            throw new pmException("文件名不能超过245字节！");
//        if( !eregi("(jpe?g|png|gif)$", $this->name) )
//            throw new pmException("文件格式不正确！请上传 jpeg/jpg/png/gif 类型的图片");
//
//        $up_dir = "attach/tmp";
//        if( !file_exists("$path/$up_dir") )
//            @mkdir("$path/$up_dir");
//        $this->temp_url = "$up_dir/$this->name";
//
//        if( !move_uploaded_file($upload['tmp_name'], "$path/$this->temp_url") )
//            throw new pmException("文件上传失败！");
//        @unlink($upload);
//    }
//
//    function save($user_id, $base_path = '.')
//    {
//        $_dir = strtoupper(substr(md5($this->name), 0, 2));
//        $photo = new pmImage("$base_path/$this->temp_url");
//
//        $path2 = "attach/nh100";
//        $this->_mkdir($base_path, $path2, $_dir);
//        $_sml = "$path2/$_dir/$this->name";
//
//        $this->cut(100, $photo);
//        $photo->DEST_URL = "$base_path/$_sml";
//        $photo->save_picture();
//
//        $path1 = 'attach/xw500';
//        $this->_mkdir($base_path, $path1, $_dir);
//        $_big = "$path1/$_dir/$this->name";
//
//        $this->zoom_width(500, $photo);
//        $photo->DEST_URL = "$base_path/$_big";
//        $photo->save_picture();
//
//        $bigph = new pmImage("$base_path/$_big");
//        $bigph->mark_domain();
//        $bigph->DEST_URL = "$base_path/$_big";
//        $bigph->save_picture();
//
//        return $this->insert($_big, $_sml, $user_id);
////        return $this;
//    }
/*
    function pm_image_zoom_max($photo, $MAX = 120)
    {
        $w = $photo->PICTURE_WIDTH ;
        $h = $photo->PICTURE_HEIGHT;
        if( $MAX > max($w, $h) )
            return ;

        if( $w > $h )
        {
           $photo->ZOOM_WIDTH  = $MAX;
           $photo->ZOOM_HEIGHT = floor( $MAX * $h / $w );
        }
        else
        {
           $photo->ZOOM_WIDTH  = floor( $MAX * $w / $h );
           $photo->ZOOM_HEIGHT = $MAX;
        }

        $photo->zoom();
        $photo->save_picture(0);
    }

    function pm_image_zoom_min($photo, $MIN = 440)
    {
        $w = $photo->PICTURE_WIDTH ;
        $h = $photo->PICTURE_HEIGHT;
        if( $MIN > min($w, $h) )
            return ;

        if( $w > $h )
        {
           $photo->ZOOM_WIDTH  = $MIN;
           $photo->ZOOM_HEIGHT = floor( $MIN * $h / $w);
        }
        else
        {
           $photo->ZOOM_WIDTH  = floor( $MIN * $w / $h);
           $photo->ZOOM_HEIGHT = $MIN;
        }

        $photo->zoom();
        $photo->save_picture(0);
    }
    */
}
?>