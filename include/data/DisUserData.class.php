<?php
/**
 * @package: DIS.DATA
 * @file   : DisUserData.class.php
 * @abstract  : 用户数据
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisUserData extends DisDBTable
{
    // 构造函数
    function __construct($user = null)
    {
        $this->table = "users";
        parent::__construct($user);
        if ( $user && (is_string($user) || is_integer($user)))
            $this->init($user);
    }

    protected function _strip_tags($detail)
    {
        $detail['username'] = strip_tags($detail['username']);
        $detail['live_city'] = strip_tags($detail['live_city']);
        $detail['contact'] = strip_tags($detail['contact']);
        $detail['sign'] = strip_tags($detail['sign']);
        $detail['self_intro'] = strip_tags($detail['self_intro']);
    }

    /**
     * 加载用户信息
     * @param string $usr 用户ID、用户名、邮箱
     * @param string $slt 加载的字段列表
     * @return soUserDataTable 加载的用户信息，加载失败则用户ID 为0
     */
    function init($usr, $slt = "ID, email, username, avatar, sign,
        gender, live_city, contact, self_intro")
    {
        if( !$usr )
            throw new DisParamException('必须传入有效的主键值。');

        if( uid_check($usr) )
            $whr = " ID = '$usr'";
        else if( name_check($usr) )
            $whr = " username = '$usr'";
        else if( email_check($usr) )
            $whr = " email = '$usr'";
        else
            throw new DisParamException('传入的参数格式不正确！');

        return $this->select($whr, $slt);
    }

    protected function _get_salt($type = null)
    {
        if( $type && $type == 'imoney' )
            $str = "select ID, salt, impassword as password, errs, last_pw_check
                from $this->table where ID = $this->ID";
        else
            $str = "select ID, salt, password from $this->table where ID = $this->ID";
        $data = parent::load_line_data($str);

        if( $data == null )
            throw new DisDBException('读取数据失败');
        if( $type && $type == 'imoney' && $data['errs'] >= 5 && $data['last_pw_check'] + 3600 * 4 > time() )
            throw new DisException('密码已经被锁定。');
        return $data;
    }

    /**
     * 用于登录框检验密码，抛出较为详细的错误信息
     * @param string $password 输入原始密码的MD5值
     * @param string $type null 或者 imoney
     * @return boolean 成功返回true
     */
    function check_password($password, $type = null)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');

        $data = $this->_get_salt($type);
        if( $data['password'] != md5($password.md5($data['salt'])) )
        {
            if( $type && $type == 'imoney' )
            {
                $str = "update $this->table set last_pw_check = ".time().", errs = errs + 1 where ID = $this->ID";
                parent::query($str);
            }
            return false;
        }

        if( $type && $type == 'imoney' && $data['errs'] > 0 )
            parent::query("update $this->table set errs = 0 where ID = $this->ID");
        return true;
    }

    /**
     * 更新密码
     * @param string $password 输入原始密码的MD5值
     * @param string $type null 或 imoney
     * @return boolean 成功返回true
     */
    function update_password($password, $type = null)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');

        $data = $this->_get_salt($type);
        $password = md5($password.md5($data['salt']));

        if( $type && $type == 'imoney' )
            $str = "update $this->table set impassword ='$password' where ID = $this->ID";
        else
            $str = "update $this->table set password ='$password' where ID = $this->ID";
        return (parent::query($str) == 1);
    }

    // 更新用户信息时，检验各字段的值是否合法
    function _check_param($name, $value)
    {
        switch($name)
        {
            case 'avatar' :
                if( !is_integer($value) )
                    return err(PMAIL_ERR_PARAM);
                break;
            case 'username' :
                if( !name_check($value) )
                    return err(PMAIL_ERR_PARAM);
                break;
            case 'password' :
            case 'impassword' :
                return err(PMAIL_SUCCEEDED);
                break;
            case 'email' :
                if( !email_check($value) )
                    return err(PMAIL_ERR_PARAM);
                break;
            case 'msg_setting' :
                if( !in_array($value, array('all', 'follow', 'channeler', 'none')) )
                    return err(PMAIL_ERR_PARAM);
                break;
            case 'phone' :
                if( !telephone_check($value) && !phone_check($value) )
                    return err(PMAIL_ERR_PARAM);
                break;
            case 'salt' :
            case 'sign' :
            case 'gender' :
            case 'contact' :
            case 'live_city' :
            case 'self_intro' :
            case 'ID_type' :
            case 'ID_number' :
                if( !is_string($value) )
                    return err(PMAIL_ERR_PARAM);
                break;
            case 'introducer' :
                if( !uid_check($value) )
                    return err(PMAIL_ERR_PARAM);
                break;
            default :
                return err(PMAIL_ERR_PARAM);
        }
        return err(PMAIL_SUCCEEDED);
    }

    static function verify_code($user_id)
    {
        $str = "select salt, email from users where ID = $user_id";
        $data = DisDBTable::load_line_data($str);
        if( !$data )
            throw new DisException('用户不存在！');
        $salt = $data['salt'];
        return substr(md5(md5($user_id).md5($salt)), 0, 8);
    }

    // 密码是原始密码的MD5值
    // 成功返回1，返回插入操作受影响的行数
    protected function insert($username, $password, $salt, $email = '', $sign = '',
            $self_intro = '', $live_city = '', $gender = 'none')
    {
        if( !$username || !$password || !$email )
            throw new DisParamException('用户名、密码，邮箱地址三项不能为空！');
        return parent::insert(array('username'=>$username, 'email'=>$email, 'password'=>$password, 'salt'=>$salt,
            'sign'=>$sign, 'self_intro'=>$self_intro, 'gender'=>$gender, 'live_city'=>$live_city));
    }

    protected function _check_num_param($num_param)
    {
        return in_array($num_param, array('rank'));
    }

    private static function _get_uid($whr)
    {
        $str = "select ID, username from users where $whr";
        $data = self::load_line_data($str);
        return $data['ID'] ? (int)$data['ID'] : 0;
    }

    static function get_uid_by_name($name)
    {
	    if( !$name || !name_check($name) )
            throw new DisParamException('用户昵称不正确！');
        return self::_get_uid("username = '$name'");
    }

    static function get_uid_by_email($email)
    {
	    if( !$email || !email_check($email) )
            throw new DisParamException('邮箱地址格式不正确！');
        return self::_get_uid("email = '$email'");
    }

    static function list_super_users()
    {
        return parent::load_datas("select user_id from user_supers");
    }
}

class DisUserParamData extends DisDBTable
{
    function  __construct($user_id = 0)
    {
        $this->table = "user_params";
        parent::__construct();
        if( $user_id > 0 )
            $this->init($user_id);
    }

    function init($user_id)
    {
        $str = "select * from user_params where ID = $user_id";
        $data = parent::load_line_data($str);
        if( $data )
        {
            $this->ID = $data['ID'];
            $this->detail = $data;
        }
        else
            $this->ID = 0;
        return $this;
    }

    protected function _check_num_param($num_param)
    {
        return in_array($num_param, array('imoney', 'online_times', 'follow_num', 'fans_num', 'msg_num',
            'mail_num', 'theme_num', 'interest_num', 'approved_num', 'reply_num', 'collect_num',
            'join_num', 'subscribe_num', 'applicant_num', 'create_num',
            'reply_notice', 'theme_notice', 'msg_notice', 'system_notice', 'fans_notice'));
    }

    // 更新用户信息时，检验各字段的值是否合法
    function _check_param($name, $value)
    {
        switch($name)
        {
            case 'ID' :
                if ( !is_integer($value) )
                    return err(PMAIL_ERR_PARAM);
                break;
            case 'imoney' :
            case 'finance' :
            case 'freezed' :
                if ( !is_numeric($value) )
                    return err(PMAIL_ERR_PARAM);
                break;
            default :
                return err(PMAIL_ERR_PARAM);
        }
        return err(PMAIL_SUCCEEDED);
    }

    function insert($id)
    {
        $str = "insert into user_params (ID, imoney) values ($id, 0)";
        parent::query($str);
        return $this->init($id);
    }

    function pay_money($imoney)
    {
        $str = "update user_params set imoney = imoney - $imoney where ID = $this->ID";
        if( parent::query($str) != 1 )
            throw new DisDBException('支付金币失败');
        $this->detail['imoney'] = (int)$this->detail['imoney'] - (int)$imoney;
    }

    /**
     * 更新当前的通知
     * @return array 返回消息通知的数组
     */
    function update_notice()
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');

        $str = "select reply_notice, fans_notice, msg_notice, theme_notice, system_notice
            from users where ID = $this->ID";
        $notices = parent::load_line_data($str);

        foreach ( $notices as $name => $value )
        {
            $this->detail[$name] = $value;
        }
        return $notices;
    }

    static function notice_name($name, $notice, $count = 1)
    {
        if( !in_array($notice, array('reply_notice', 'theme_notice', 'system_notice',
            'fans_notice', 'msg_notice')) || $count == 0 )
            throw new DisParamException('参数不合法！');

        $str = "update users set $notice = $notice + $count where username = '$name'";
        return self::query($str) == 1;
    }

    /**
     * 通知用户
     * @param string $notice 通知项目
     * @param integer $count 改变值，不是绝对值
     * @return integer 成功返回1，失败返回0，是执行SQL语句影响的行数
     */
    function notice($notice, $count = 1)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !in_array($notice, array('reply_notice', 'flighty_notice', 'atme_notice', 'fans_notice', 'msg_notice'))
                || $count == 0 )
            throw new DisParamException('参数不合法！');

        $value = max(0, $this->detail[$notice] + $count);
        if( $value == $this->detail[$notice] )
            throw new DisParamException('参数不合法！');
        $str = "update users set $notice = $value where ID = $this->ID";
        $r = self::query($str);
        if( $r == 1 )
            $this->detail[$notice] = $value;
        return $r;
    }
}

class DisUserRelationData extends DisObject
{
    static function load($id)
    {
        $str = "select ID, from_user, to_user from user_relations where ID = $id";
        return DisDBTable::load_line_data($str);
    }

    /**
     * 查询用户$fuid是否关注用户$tuid
     * @param <integer> $fuid
     * @param <integer> $tuid
     * @return <boolen> 是否已经建立关注关系
     */
    static function relation($fuid, $tuid)
    {
        $r = DisDBTable::count("from user_relations where `from_user` = $fuid and `to_user` = $tuid");
        return $r == 1;
//	    return DisDBTable::count("from user_relations where `from_user` = $fuid and `to_user` = $tuid") == 1;
    }

    /**
     * 本函数不检查$fuid和$tuid之间是否已经建立关注关系
     * @param <integer> $fuid
     * @param <integer> $tuid
     * @return <integer> 返回影响的行数
     */
    static function insert($fuid, $tuid)
    {
        if( !is_integer($fuid) || !is_integer($tuid) )
            throw new DisParamException('用户ID格式不正确！');

        $str = "insert into user_relations (`from_user`, `to_user`) values ($fuid, $tuid)";
        $r = DisDBTable::query($str);
        if ( $r != 1 )
            throw new DisDBException('关注用户失败！');
        return DisDBTable::last_insert_Id();
    }

    /**
     * 用户已经读取了该关注关系
     * @param <integer> $rid
     * @return <boolean> 是否成功
     */
    static function read($rid)
    {
        $str = "update user_relations set `read` = 1 where ID = $rid";
        return DisDBTable::query($str) == 1;
    }

    static function remove($fuid, $tuid)
    {
        if( !self::relation($fuid, $tuid) )
            return true;
        $str = "delete from user_relations
            where `from_user` = $fuid and `to_user` = $tuid";
        return DisDBTable::query($str) == 1;
    }

    /**
     * 读取用户的所有粉丝
     * @param integer $user_id 用户ID
     * @return array 用户的粉丝数组
     */
    static function fans($user_id, $page = 0, $count = 20)
    {
	    $str = "select ID, `from_user`, `to_user` `read` from user_relations
            where `to_user` = $user_id order by from_user ";
//        echo $str ;
	    return DisDBTable::load_datas($str);
    }

    /**
     * 读取所有关注对象
     * @param integer $user_id 用户ID
     * @return array 关注的用户数组
     */
    static function follows($user_id)
    {
	    $str = "select ID, `from_user`, `to_user`, `read` from user_relations
            where `from_user` = $user_id order by to_user";
	    return DisDBTable::load_datas($str);
    }

    static function load_treasure_users($user_id, $page = 0, $count = 20, $keyword = '')
    {
        $str = "select r.to_user as ID from user_relations as r where r.from_user = $user_id";
        if ( isset($keyword) && !empty($keyword) && $keyword != '' )
        {
            $keyword = keyword_parse($keyword);
            $count = count($keyword);
            if ( $count > 1 )
            {
                $str = "select r.to_user as ID from user_relations as r, user_tags as t where
                    where r.to_user = t.user_id and r.from_user = $user_id and (t.tag = ".$keyword[0];
            }
            for ( $i = 1; $i < $count; $i ++ )
                $str .= "and t.tag = ".$keyword[$i];
            $str .= ")";
        }
        $str = " order by r.ID desc limit ".$page * $count.", $count";
        return DisDBTable::load_datas($str);
    }
}

class DisUserDenyData extends DisObject
{
    static function deniers($user)
    {
        $str = "select ID, user_id, denier from user_denies where user_id = $user";
        return DisDBTable::load_datas($str);
    }

    static function insert($user, $denier)
    {
        $str = "insert into user_denies (user_id, denier) values ($user, $denier)";
        if( DisDBTable::query($str) != 1 )
            throw new DisDBException('插入失败！');
        return DisDBTable::last_insert_Id();
    }

    static function delete($did)
    {
        return DisDBTable::query("delete from user_denies where ID = $did") == 1;
    }
}
?>