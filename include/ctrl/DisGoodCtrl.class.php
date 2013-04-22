<?php
/**
 * @package: DIS.CTRL
 * @file   : DisGoodCtrl.class.php
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

class DisGoodCtrl extends DisGoodData
{
    // 构造函数
    function __construct($good_id = 0)
    {
        parent::__construct($good_id);
    }

    static function get_data($good_id)
    {
//        pmRowMemcached::set_good_data($good_id, null);
        $good = DisRowCache::get_good_data($good_id);

        if( !$good )
        {
            $gc = new DisGoodCtrl((int)$good_id);
            if( $gc && $gc->ID > 0 )
            {
                $good = $gc->info();
                $good['user'] = DisUserCtrl::get_data($good['user_id']);
            }
            else
                $good = array('ID'=>0, 'desc'=>'商品不存在或者已经被删除');
            DisRowCache::set_good_data($good_id, $good);
        }

        return $good;
    }

    static function good($good_id)
    {
        $good = new DisGoodCtrl();
        $good->ID = $good_id;
        $good->detail = self::get_data($good_id);
        return $good;
    }

    function save($user_id)
    {
        parent::insert($user_id, $this->detail['source'], $this->detail['num_iid'],
            $this->detail['title'], $this->detail['price'], $this->detail['click_url'], $this->detail['pic_url'],
            $this->detail['shop'], $this->detail['shop_url'], $this->detail['item_location']);
        //self::insert_tags($this->ID, $this->attr('desc'));
        DisRowCache::set_good_data($this->ID, $this->detail);
    }

    function reduce($param, $step = 1)
    {
        parent::reduce($param, $step);
        $info = $this->info();
        $info['user'] = DisUserCtrl::get_data($this->attr('user_id'));
        DisRowCache::set_good_data($this->ID, $info);
    }

    function increase($param, $step = 1)
    {
        parent::increase($param, $step);
        $info = $this->info();
        $info['user'] = DisUserCtrl::get_data($this->attr('user_id'));
        DisRowCache::set_good_data($this->ID, $info);
    }

//    /**
//     * 保存信息所含有的关键词
//     * @param integer $good_id 图片ID
//     * @param string $content 信息内容
//     * @return integer 插入关键词的个数
//     */
//    static function insert_tags($good_id, $content)
//    {
//        $rsg = '/#([\w\x{4e00}-\x{9fa5}]+)#/ui';
//        if( preg_match_all($rsg, $content, $matches) )
//            $tags = $matches[1];
//        else
//            return;
//
//        $len = count($tags);
//        for( $i = 0; $i < $len; $i ++ )
//            soGoodTagDataTable::insert($good_id, $tags[$i]);
//    }
}
?>