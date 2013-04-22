/*!
 * PMAIL项目 JavaScript v2.4.12
 *
 * @bref 网购商品
 * @author 朱继玉
 * @copyright @2012 公众邮件网
 */
pmGood = function(selector, context)
{
    this.selector = selector;
    this.context = context;
    this[0] = this.object = $(selector, context);

    this.id = this[0].attr('id');
    this.num_iid = this[0].attr('numiid');
    this.source = this[0].attr('source');
    return this;
};

pmGood.prompttext = '\u76ee\u524d\u8fd8\u4e0d\u652f\u6301\u8be5\u7f51\u7ad9\u7684\u5546\u54c1\uff01';
pmGood.source = {tmall: "\u5929\u732b\u5546\u57ce", taobao: "\u6dd8\u5b9d\u7f51"};

pmGood.fn = pmGood.prototype =
{
    id: 0, source: 'tmall', url: '', num_iid: 0,

    init : function()
    {
        this[0].addClass('pm-mail-good pm-object');
        if( $('.info', this[0]).length === 0 )
            this[0].append($('<div class="info"><span class="price"></span>：<a class="source" target="_blank"></a><br><a class="title" target="_blank"></a></div>'));
        if( $('.photo', this[0]).length === 0 )
            this[0].append($('<div class="photo"></div>'));
    },

    tmall : function(_good)
    {
        var _target = this[0];

        _target.attr('id', _good.ID);
        _target.attr('numiid', _good.num_iid);
        _target.attr('source', _good.source);

        $('.title', _target).text(_good.title);
        $('.title', _target).attr('href', this.url);
        $('.photo', _target).append('<img src="' + _good.pic_url + '">');
        $('.price', _target).text(_good.price + '元');
        $('.source', _target).text(pmGood.source.tmall);
        $('.source', _target).attr('href', 'www.tmail.com');

        return this;
    },

    taobao: function(_good)
    {
        var _target = this[0];
        this.tmall(_good);
        $('.source', _target).text(pmGood.source.taobao);
        $('.source', _target).attr('href', 'www.taobao.com');
        return this;
    },

    parse : function(_url, _options)
    {
        var _idReg, _good = this,
            options = $.extend(
            {
                start: function(s){},
                end: function(s){},
                error: function(s){},
                success: function(s){}
            }, _options);

        this.num_iid = 0;
        this.url =  _url;

        if( _url === undefined || typeof _url !== 'string' )
        {
            options.error('\u5546\u54c1\u94fe\u63a5\u4e0d\u80fd\u7a7a\uff01');
            return this;
        }

        if( pm.regs.taobao.exec(_url) )
        {
            _idReg = /id=(\d+)/i.exec(_url);
            this.num_iid = _idReg[1];
            this.source = 'taobao';
        }
        else if( pm.regs.tmall.exec(_url) )
        {
            _idReg = /id=(\d+)/i.exec(_url);
            this.num_iid = _idReg[1];
            this.source = "tmall";
        }
        else if( pm.regs.jingdong.exec(_url) )
        {
            this.source = 'jingdong';
        }
        else
        {
            options.error(pmGood.prompttext);
            return this;
        }

        if( this.num_iid === 0 )
        {
            options.error(pmGood.prompttext);
            return this;
        }

        options.start();

        $.get('api/pmail.api.good.php',
        {
            p: 'parse',
            url: _good.url,
            id: _good.id,
            num_iid: _good.num_iid,
            src: _good.source
        },

        function(data)
        {
            options.end();
            if( data.err )
            {
                options.error(data.err);
            }
            else
            {
                _good.id = data.good.ID;
                _good.init();
                _good.save(data.good);
                _good.cart();
                options.success(data.good);
                $('#good_id', _good[0]).val(data.good.ID);
            }
        }, 'json');

        return this;
    },

    save : function($good)
    {
        this.good = $good;
        if( this.source === 'tmall' )
            this.tmall($good);
        else if( this.source === 'taobao' )
            this.taobao($good);
    },

    cart : function()
    {
        var _src = $(this[0]).attr('source');
        if( $('.cart', this[0]).length === 0 )
            $(this[0]).append('<div class="cart"><img></div>');
        if( _src === 'taobao' )
            $('.cart img', this[0]).attr('src', 'css/logo/taobao.gif');
        else if( _src === 'tmall' )
            $('.cart img', this[0]).attr('src', 'http://a.tbcdn.cn/p/mall/base/favicon2.ico');
    }
};

pm.fn.extend(
{
    good : function()
    {
        this.good = new pmGood(this[0]);
        this.good.cart();
        return this;
    }
});
