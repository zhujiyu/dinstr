<?php
/**
 * @package: DIS.DATA
 * @file   : DisUserParamData.class.php
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