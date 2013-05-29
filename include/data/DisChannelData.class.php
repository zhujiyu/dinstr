<?php
/**
 * @package: DIS.DATA
 * @file   : DisChannelData.class.php
 * @abstract  : 频道数据
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisChannelData extends DisDBTable
{
    // 构造函数
    function __construct($id = null)
    {
        $this->table = "channels";
        parent::__construct();
        if( (int)$id > 0 )
            $this->init($id);
    }

    protected function _strip_tags(&$detail)
    {
        $detail['name'] = strip_tags($detail['name']);
        $detail['desc'] = strip_tags($detail['desc']);
        $detail['bulletin'] = strip_tags($detail['bulletin']);
    }

    function init($chan, $slt = "ID, name, logo, `type`, `desc`, bulletin, creater,
            info_num, member_num, subscriber_num, applicant_num, create_time")
    {
        if( uid_check($chan) )
            parent::init($chan, $slt);
        else if( name_check($chan) )
            $this->select("name = '$chan'", $slt);
        else
            throw new DisParamException('参数类型不正确！');
        return $this;
    }

    function _check_param($name, $value)
    {
        switch($name)
        {
            case 'name':
                if( !name_check($value) )
                    return err(DIS_ERR_PARAM);
                break;
//            case 'domain' :
//                if( !domain_check($value) )
//                    return err(DIS_ERR_PARAM);
//                break;
            case 'type':
                if( !in_array($value, array('social', 'business', 'info', 'news')) )
                    return err(DIS_ERR_PARAM);
                break;
            case 'creater':
                if( !uid_check($value) )
                    return err(DIS_ERR_PARAM);
                break;
            case 'logo':
                if( !is_integer($value) )
                    return err(DIS_ERR_PARAM);
                break;
            case 'bulletin':
            case 'desc':
                if( !is_string($value) )
                    return err(DIS_ERR_PARAM);
                break;
            default :
                return err(DIS_ERR_PARAM);
        }
        return err(DIS_SUCCEEDED);
    }

    protected function _check_num_param($param)
    {
        return in_array($param,
                array('info_num', 'member_num', 'subscriber_num', 'applicant_num'));
    }

    protected function _name_exist($name)
    {
        return $this->row_exist(array('name'=>$name));
    }

    static function name_exist($name)
    {
        $o = new DisChannelData();
        return $o->row_exist(array('name'=>$name));
    }

    /**插入一个新的频道
     * @global string $salt 生成一个随机数
     * @param integer $creater 创建者
     * @param string $name 频道的名字
     * @param string $type 频道类型
     * @param string $logo 标志图片
     * @param string $description 介绍说明
     * @return DisChannelData 对象
     */
    function insert($creater, $name, $type = 'social', $logo = 0,
            $desc = '对该频道进行简短描述')
    {
        if ( !$creater || !$name )
            throw new DisParamException('名称不能为空！');
        if ( $this->_name_exist($name) )
            throw new DisParamException('该名称已经被占用！');
        return parent::insert( array('creater'=>$creater, 'name'=>$name, 'type'=>$type,
            'logo'=>$logo, 'desc'=>$desc) );
    }

    static function get_id_by_name($name)
    {
        $str = "select ID from channels where name = '$name'";
        $data = DisDBTable::load_line_data($str);
        return $data ? $data['ID'] : 0;
    }

    static function get_id_by_domain($domain)
    {
        $str = "select ID from channels where domain = '$domain'";
        $data = DisDBTable::load_line_data($str);
        return $data ? $data['ID'] : 0;
    }

    static function list_latest_channels($date, $page = 0, $count = 20,
        $slt = "ID, name, logo, `type`, description, announce, creater,
        info_num, member_num, applicant_num, subscriber_num, create_time")
    {
        $str = "select $slt
            from channels where create_time > $date
            order by ID desc limit ".$page * $count.", $count";
        return DisDBTable::load_datas($str);
    }

    static function list_channels($page = 0, $count = 20,
            $slt = "ID, name, logo, `type`, description, announce, creater,
            info_num, member_num, applicant_num, subscriber_num, create_time")
    {
        $str = "select $slt from channels as c
            order by ID desc limit ".$page * $count.", $count";
        return DisDBTable::load_datas($str);
    }
}
?>