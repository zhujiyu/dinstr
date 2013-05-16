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
        $detail['sign'] = strip_tags($detail['sign']);
        $detail['live_city'] = strip_tags($detail['live_city']);
        $detail['contact'] = strip_tags($detail['contact']);
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
            $str = "select ID, salt, impassword as password, check_errs, last_check
                from $this->table where ID = $this->ID";
        else
            $str = "select ID, salt, password from $this->table where ID = $this->ID";

        $data = parent::load_line_data($str);
        if( $data == null )
            throw new DisDBException('读取数据失败');
        if( $type == 'imoney' && $data['check_errs'] >= 5 && $data['last_check'] + 3600 * 4 > time() )
            throw new DisPWException('密码已经被锁定。');
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
//        if( $data['password'] != md5($password.md5($data['salt'])) )
        if( $data['password'] != md5($password.$data['salt']) )
        {
            if( $type == 'imoney' )
            {
                $str = "update $this->table set last_check = ".time().", check_errs = check_errs + 1
                    where ID = $this->ID";
                if( !parent::check_query($str, 1) )
                    throw  new DisDBException("验证安全密码失败。");
//                parent::query($str);
            }
            return false;
        }

        if( $type == 'imoney' && $data['check_errs'] > 0 )
            parent::query("update $this->table set check_errs = 0 where ID = $this->ID");
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
        $password = md5($password.$data['salt']);
        if( $data['password'] == $password )
            return true;

        if( $type == 'imoney' )
            $str = "update $this->table set impassword ='$password' where ID = $this->ID";
        else
            $str = "update $this->table set password ='$password' where ID = $this->ID";
        return parent::check_query($str, 1);
    }

    // 更新用户信息时，检验各字段的值是否合法
    protected function _check_param($name, $value)
    {
        switch($name)
        {
            case 'avatar' :
                if( !is_integer($value) )
                    return err(DIS_ERR_PARAM);
                break;
            case 'username' :
                if( !name_check($value) )
                    return err(DIS_ERR_PARAM);
                break;
            case 'password' :
            case 'impassword' :
                return err(DIS_SUCCEEDED);
                break;
            case 'email' :
                if( !email_check($value) )
                    return err(DIS_ERR_PARAM);
                break;
            case 'msg_setting' :
                if( !in_array($value, array('all', 'follow', 'channeler', 'none')) )
                    return err(DIS_ERR_PARAM);
                break;
            case 'phone' :
                if( !telephone_check($value) && !phone_check($value) )
                    return err(DIS_ERR_PARAM);
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
                    return err(DIS_ERR_PARAM);
                break;
            case 'introducer' :
                if( !uid_check($value) )
                    return err(DIS_ERR_PARAM);
                break;
            default :
                return err(DIS_ERR_PARAM);
        }
        return err(DIS_SUCCEEDED);
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
            $self_intro = '', $gender = 'none', $live_city = '')
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
?>