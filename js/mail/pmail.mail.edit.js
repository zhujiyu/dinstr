/*!
 * PMAIL项目 JavaScript v2.4.12
 *
 * @bref 信息操作的基础类
 * @author 朱继玉
 * @copyright @2012 公众邮件网
 */
pmRuler = function ()
{
    this.ruler = $('<div class="pm-mail-weight">\
<input type="text" name="weight" class="pm-no-border" value="设置优先级"></input>\
<div class="pm-ruler pm-inline-block" title="">\
<div class="pm-tag-list"></div>\
<div class="pm-mark-list"><div class="pm-first-mark pm-inline-block"></div></div>\
<div class="pm-ruler-line"></div>\
</div></div>');

    for( var i = 0, _txt = 1; i < 5; i ++ )
    {
        $('.pm-mark-list', this.ruler).append('<div class="pm-mark pm-inline-block"></div>');
        $('<div class="pm-tag pm-inline-block"></div>').text(_txt).appendTo($('.pm-tag-list', this.ruler));
        _txt *= 10;
    }
    $('.pm-tag:first', this.ruler).text(1);

    var _ruler = this;
    $('input', this.ruler).blur(function()
    {
        var _val = _ruler.unPreg($(this).val());
        $('.pm-ruler-line', _ruler.ruler).width(_val);
        $(this).val(_ruler.preg(_val));
    });

    $('.pm-ruler', this.ruler).mousedown(function()
    {
        $('input', $(this).parent()).val($(this).attr('title'));
        $('.pm-ruler-line', _ruler.ruler).width(_ruler.unPreg($(this).attr('title')));
    }).
    mousemove(function(e)
    {
        var _left = e.clientX - $(this).offset().left;
        $(this).attr('title', _ruler.preg(_left));
    });

    return this;
};

pmRuler.fn = pmRuler.prototype =
{
    // 把点击的长度，转换成优先级的值
    preg : function (_left)
    {
        if( _left < 10 )
            return 0;
        else if( _left <= 19 )
            return 1;
        _left -= 5;

        var _d = parseInt(_left / 90);
        var _s = parseInt(_left % 90);
        var _b = parseInt(_s / 10) +1;
        for( var a = 0; a < _d; a ++ )
            _b *= 10;
        return _b;
    },

    unPreg : function (_v)
    {
        _v = parseInt(_v);
        if( isNaN(_v) )
            _v = 0;

        for( var a = 0, b = _v; b > 10; a ++ )
            b = parseInt(b / 10);
        return a * 90 + b * 10 + 0;
    }
};

pmTarget = function(_options)
{
    this.options = $.extend(
    {
        promptText: "+ 接收频道"
    }, _options);

    this.ruler = (new pmRuler()).ruler;
    this.chan = $('<div class="pm-border pm-mail-target">\
<table class="pm-layout-table"><tr>\
<input type="hidden" id="channel_id" name="channel_id" value="0"></input>\
<td class="pm-channel-logo"><div class="pm-avatar-img pm-avatar-small"><img src="css/logo/chanbgs.png"></div></td>\
<td class="pm-channel-add"><input type="text" class="pm-no-border" id="add-channel" value="+ 接收频道"></input></td>\
<td class="pm-channel-desc">&nbsp;</td><td>&nbsp;</td>\
</tr></table></div>').append('<div class="pm-border-line"></div>').append(this.ruler);

    var _trgt = this;
    this.input = $('input#add-channel', this.chan).unbind('focus').unbind('blur').autocomplete(
    {
        minLength: 0,

        select : function(event, ui)
        {
            var _uivc = ui.item.channel,
                _desc = _uivc.description,
                _trgt = $(this).parents('.pm-mail-target');

            _desc = _desc.length > 20 ? _desc.substr(0, 20) + '...' : _desc;
            $('#channel_id', _trgt).val(_uivc.ID);
            $('.pm-channel-desc', _trgt).text(_desc);
            $('.pm-channel-logo img', _trgt).attr('src', _uivc.logo.small);

            $('.pm-mail-weight', _trgt).hide().show(function()
            {
                $('.pm-mail-weight input', _trgt).focus();
            });
        },

        open: function(event, ui)
        {
            var _chns = $('.ui-autocomplete'),
                _trgt = $(this).parents('.pm-mail-target');
            $('.pm-border-line:last', _chns).remove();
            pm('.pm-dropdown-icon', _chns).loadImg();
            _chns.css({'min-width' : ( _trgt.width() - 48) + 'px'});
        }
    }).

    focus(function()
    {
        _trgt.empty();
        if( pmTarget.cache.length > 0 )
            _trgt.source();
        else
            _trgt.load();
    }).

    blur(function()
    {
        if( $(this).val() !== '' )
            return ;
        $(this).val(_trgt.options.promptText);
        _trgt.empty();
    });

    return this;
};

pmTarget.cache = [];

pmTarget.fn = pmTarget.prototype =
{
    empty : function()
    {
        this.ruler.hide();
        $('#channel-id', this.chan).val(0);
        $('.pm-channel-desc', this.chan).html('&nbsp;');
        $('.pm-channel-logo img', this.chan).attr('src', 'css/logo/chanbgs.png');
    },

    source : function()
    {
        this.input.autocomplete({source: pmTarget.cache.slice(0)}).data( "autocomplete" )._renderItem = function( ul, item )
        {
            return $( "<li></li>" ).data( "item.autocomplete", item )
                    .append( "<a>" + pm.renderStr(item.channel) + "</a>" ).appendTo( ul );
        };
        this.input.val('').autocomplete('search', '');
    },

    load : function()
    {
        var _chan = this;

        $.get('api/pmail.api.channel.php',
        {
            p: 'list'
        },

        function(data)
        {
            if( data.msg )
            {
                pm('<div>').tip({content: data.msg});
                return;
            }

            for( var i = 0; i < data.channels.length; i ++ )
            {
                pmTarget.cache[i] = {id: data.channels[i].ID, label: data.channels[i].name,
                    desc: data.channels[i].description, channel: data.channels[i]};
            }
            _chan.source();
//            $.cookie('pm-channel-cache', $.json_encode({user_id: $('#userid').val(), channels: pmTarget.cache}), {expires: 30});
        }, 'json');
    }
//
//    write : function(_cache)
//    {
//        var _ids = new Array(), _cookie;
//
//        for( var i = 0; i < _cache.length; i ++ )
//            _ids.push(_cache[i].id);
//
//        _cookie = $.json_encode({user_id: $('#userid').val(), ids: _ids});
//        $.cookie('cache-channel-ids', _cookie, {expires: 30});
//
//        for( var i = 0; i < _cache.length; i ++ )
//        {
//            _cookie = $.json_encode(_cache[i].channel);
//            $.cookie('cache-channel' + _cache[i].id, _cookie, {expires: 30});
//        }
//        return this;
//    },
//
//    read : function()
//    {
//        var _ids, _cache = new Array(), _channel,
//            _cookie = $.parseJSON($.cookie('cache-channel-ids'));
//
//        if( _cookie.user_id !== $('#userid').val())
//            return null;
//        _ids = _cookie.ids;
//
//        for( var i = 0; i < _ids.length; i ++ )
//        {
//            _channel = $.parseJSON($.cookie('cache-channel' + _cache[i].id));
//            _cache[i] = {id : _ids[i], desc : _channel.description, label : _channel.name, channel : _channel};
//        }
//        return _cache;
//    }
};

pmGood = function(selector, context)
{
    this.selector = selector;
    this.context = context;
    this.object = $(selector, context);
    this[0] = $(selector, context).append($('<div class="pm-object pm-mail-good pm-mail-good-small" goodid="" source="" numiid="">\
<input type="hidden" name="good_ids[]" id="good_id"></input>\
<div class="pm-good-photo pm-tile-img pm-inline-block" imgsrc=""><img /></div>\
<div class="pm-good-info pm-inline-block">\
<div class="pm-good-title title">商品名称：<a href="" target="_blank"></a></div>\
<div class="pm-good-info-main">价格：\
<span class="price"><img src=""/></span>\
<span class="source">来自：<a target="_blank"></a></span>\
</div>\
<div class="pm-good-info-extend">\
<span class="shop">店铺：<a target="_blank"></a></span>\
<span class="location">地址：<span></span></span>\
</div>\
<div class="pm-good-edit-desc">\
<textarea name="good_descs[]" id="good_desc"></textarea>\
</div>\
</div></div>'));
    return this;
};

pmGood.prompttext = '\u76ee\u524d\u8fd8\u4e0d\u652f\u6301\u8be5\u7f51\u7ad9\u7684\u5546\u54c1\uff01';
pmGood.source = {tmall: "\u5929\u732b\u5546\u57ce", taobao: "\u6dd8\u5b9d\u7f51"};

pmGood.fn = pmGood.prototype =
{
    id: 0, source: 'tmall', url: '', num_iid: 0,

    tmall : function(_good)
    {
        var _target = this[0];
        $('.pm-mail-good', _target).attr('numiid', _good.num_iid);
        $('.pm-mail-good', _target).attr('source', _good.source);
        $('.pm-mail-good', _target).attr('goodid', _good.ID);
        $('.pm-good-photo img', _target).attr('src', _good.pic_url);
        $('.pm-good-title a', _target).text(_good.title);
        $('.pm-good-title a', _target).attr('href', this.url);

        $('.price', _target).empty();
        $('.price', _target).text(_good.price);
        $('.source a', _target).text(pmGood.source.tmall);
        $('.source a', _target).attr('href', 'www.tmail.com');
        $('.shop a', _target).text(_good.nick);
        $('.shop a', _target).attr('href', 'www.tmail.com');
        return this;
    },

    taobao: function(_good)
    {
        this.tmall(_good);
        $('.source a', this[0]).text(pmGood.source.taobao);
        return this;
    },

    pretreat: function(_url)
    {
        var _id_reg;
        this.num_iid = 0;
        this.url = _url;

        if( _url === undefined || typeof _url !== 'string' )
        {
            pm('<div>').tip({mess: '\u5546\u54c1\u94fe\u63a5\u4e0d\u80fd\u7a7a\uff01'});
            return this;
        }

        if( pm.regs.taobao.exec(_url) )
        {
            _id_reg = /id=(\d+)/i.exec(_url);
            this.num_iid = _id_reg[1];
            this.source = 'taobao';
        }
        else if( pm.regs.tmall.exec(_url) )
        {
            _id_reg = /id=(\d+)/i.exec(_url);
            this.num_iid = _id_reg[1];
            this.source = "tmall";
        }
        else if( pm.regs.jingdong.exec(_url) )
        {
            this.source = 'jingdong';
        }
        else
        {
            pm('<div>').tip({content: pmGood.prompttext});
        }

        return this;
    },

    parse : function(_url, _options)
    {
        var
            _good = this,
            _func = function(s){},
            options = $.extend(
            {
                start: _func,
                end: _func,
                error: _func,
                success: _func
            }, _options);

        this.pretreat(_url);

        if( this.num_iid === 0 )
        {
            if( typeof options.error !== 'undefined' && $.isFunction(options.error) )
                options.error(pmGood.prompttext);
            if( typeof options.end !== 'undefined' && $.isFunction(options.end) )
                options.end();
        }

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
            if( data.err )
            {
                pm('<div>').tip({content: data.err});
                if( typeof options.error !== 'undefined' && $.isFunction(options.error) )
                    options.error(data.err);
            }
            else
            {
                _good.save(data.good);
                $('#good_id', _good[0]).val(data.good.ID);
                if( options.success !== undefined )
                    options.success(data.good);
            }

            if( typeof options.end !== 'undefined' && $.isFunction(options.end) )
                options.end();
        }, 'json');

        return this;
    },

    save : function(_good)
    {
        this.good = _good;
        if( this.source === 'tmall' )
            this.tmall(_good);
        else if( this.source === 'taobao' )
            this.taobao(_good);
    }
};

_pMail.fn.extend(
{
    tipParsing : function (str)
    {
        var _load = $('<div id="parsing" class="pm-load"><div class="ui-widget-overlay"></div></div>')
            .prependTo(this[0]).css({'position': 'absolute'}).fadeIn('normal');
        $(this[0]).css({'position': 'relative'});
        $('<span></span>').text(str).appendTo(_load);
        $('#parsing span', this[0]).css({'top': Math.floor((_load.height() - $('#parsing span', this[0]).height() - 10) / 2) + 'px'});
    },

    removeParsing: function()
    {
        $('#parsing', this[0]).remove();
    }
});

pmEdit = function(_owner, _submit, _custom)
{
    this.edit = $('<form class="pm-mail-edit" method="post" action="mail">\
<input type="hidden" name="p" id="p" value="reply"/>\
<input type="hidden" name="parent" id="parent" value="0"/>\
<div class="pm-border pm-mail-target"></div>\
<div class="pm-border pm-mail-content"><textarea class="pm-no-border" id="content" name="content">邮件内容</textarea></div>\
\
<ul id="sortable" class="pm-object-list"></ul>\
<div class="pm-good-parse-ctrl">\
<div>添加网购商品(可选)，请将商品的网址复制到下面的输入框中</div>\
<div class="pm-border pm-inline-block">\
<a class="pm-light-button pm-parse-good">添加</a><input type="text" id="url" class="pm-no-border"></input>\
</div>\
<div class="pm-inline-block"><a class="pm-manual" href="good?p=input" target="_blank">手动输入</a></div>\
</div>\
<div class="pm-upload-ctrl">\
<div>添加图片（可选）</div>\
<input type="file" id="pm-upload-img" name="pm-upload-img"></input>\
<a class="pm-light-button pm-upload-file-button">上传</a>\
<img class="uploading" src="css/images/loading.gif"/>\
</div>\
\
<table class="pm-ctrl-table pm-layout-table"><tr><td><div class="pm-edit-tool">\
<a class="pm-display-good"><span class="pm-inline-block pm-ctrl-icon"><span class="pm-icon ui-icon-suitcase"></span></span><span class="text">商品</span></a>\
<a class="pm-display-photo"><span class="pm-inline-block pm-ctrl-icon"><span class="pm-icon ui-icon-image"></span></span><span class="text">图片</span></a>\
<a class="pm-display-photo"><span class="pm-inline-block pm-ctrl-icon"><span class="pm-icon ui-icon-comment"></span></span><span class="text">地图</span></a>\
</div></td><td class="pm-ctrl pm-mail-ctrl">\
<a id="cancel" class="pm-gray-button">取消</a><a id="ok" class="pm-light-button pm-publish">发送</a>\
<input type="submit" value="发布"></input>\
</td></tr></table>\
</form>').appendTo(_owner);

    $('.pm-mail-content', this.edit).before('<div class="pm-mail-title pm-border"><textarea class="pm-no-border" name="title" id="title">邮件标题</textarea></div>');
    pm('.pm-mail-content #content', this.edit).promptText('\u90ae\u4ef6\u5185\u5bb9');
    $('.pm-mail-content #content', this.edit).autoResize({auto: false});

    pm('.pm-mail-title #title', this.edit).promptText();
    $('.pm-mail-title #title', this.edit).autoResize(
    {
        extraSpace: 0,
        onResized: function()
        {
            $(this).parent().height($(this).height() + 5);
        }
    });

    this.target = new pmTarget();
    $('.pm-mail-target', this.edit).replaceWith(this.target.chan);
    $('.pm-mail-weight', this.edit).hide();
    $('#sortable', this.edit).sortable({placeholder: "pm-channel-shirt"});
    pm('.pm-mail-weight input', this.edit).promptText('\u8bbe\u7f6e\u4f18\u5148\u7ea7');

    var _pedit  = this;
    $('.pm-display-good', this.edit).click(function()
    {
        $('.pm-good-parse-ctrl', _pedit.edit).show();
        return false;
    });
    $('.pm-display-photo', this.edit).click(function()
    {
        $('.pm-upload-photo-ctrl', _pedit.edit).show();
        return false;
    });

    $('.pm-upload-file-button', this.edit).click(function()
    {
        pm.upload($('.pm-upload-photo-ctrl', _pedit.edit),
        {
            loaded : function(photo)
            {
                var _photo = $('<li class="pm-object pm-input-photo ui-corner-all">\
<input type="hidden" name="photo_ids[]" id="photo_id"></input>\
<input type="hidden" name="photo_urls[]" id="photo_url"></input>\
<div class="pm-photo pm-tile-img pm-inline-block" imgstr=""><img /></div> \
<div class="pm-input-photo-desc pm-inline-block"> \
<textarea name="photo_descs[]" id="photo_desc"></textarea> \
</div></li>');

                $('.pm-object-list', _pedit.edit).append(_photo).show();
                $('.pm-photo', _photo).attr('imgsrc', photo.small);
                $('#photo_url', _photo).val(photo.small);
                $('#photo_id', _photo).val(photo.ID);

                pm(_photo).close();
                pm('.pm-photo', _photo).loadImg();
            }
        });
        return false;
    });

    var _parse = $('.pm-good-parse-ctrl', this.edit);

    $('.pm-parse-good', _parse).click(function()
    {
        var _good = new pmGood('<li class="ui-corner-all"></li>');
        _good.parse($('#url', _parse).val(),
        {
            start: function()
            {
                pm(_parse).tipParsing('\u6b63\u5728\u89e3\u6790...');
            },

            end : function()
            {
                pm(_parse).removeParsing();
                $('#url', _parse).val('');
            },

            success: function()
            {
                $('.pm-object-list', _pedit.edit).append(_good[0]).show();
                pm(_good[0]).close();
            }
        });
        return false;
    });

    $(".pm-publish", this.edit).click(function()
    {
        if( $(".pm-mail-content textarea", _pedit.edit).val() !== '' )
            $(_pedit.edit).submit();
        return false;
    });

    if( _custom !== undefined && $.isFunction(_custom) )
        _custom(_pedit.edit, _pedit);

    this.prompt =
    {
        title: $('.pm-mail-title #title', this.edit).text(),
        content: $('.pm-mail-content #content', this.edit).val()
    };

    $(this.edit).submit(function()
    {
        var _rank = 0, _list = $('.pm-object-list', this);
        $('.pm-object', _list).each(function()
        {
            $(this).attr('rank', _rank);
            _rank ++;
        });

        _pedit.goods = new Array();
        $('.pm-mail-good', _list).each(function()
        {
            _pedit.goods.push({id: $('#good_id', this).val(), desc: $('#good_desc', this).val(), rank: $(this).attr('rank')});
        });
        _pedit.photos = new Array();
        $('.pm-input-photo', _list).each(function()
        {
            _pedit.photos.push({id: $('#photo_id', this).val(), desc: $('#photo_desc', this).val(),
                rank: $(this).attr('rank')});
        });

        _pedit.parent = $('#parent', this).val();
        _pedit.chan_id = $('.pm-mail-target #channel_id', this).val();
        _pedit.weight = $('.pm-mail-weight input', this).val();
        _pedit.title = $('.pm-mail-title #title', this).val();
        _pedit.content = $('.pm-mail-content #content', this).val();

        if( _submit !== undefined && $.isFunction(_submit) )
            _submit($(this), _pedit);
        return false;
    });

    return this;
};

pmEdit.fn = pmEdit.prototype =
{
    title: '', content: '', goods: [], photos: [],

    complete : function()
    {
        $('.pm-object-list', this.edit).empty().hide();
        $('.pm-upload-photo-ctrl, .pm-good-parse-ctrl', this.edit).hide();
        $('.pm-mail-content textarea', this.edit).val('');
        $('.pm-mail-weight input', this.edit).val(0);
        this.target.empty();
    }
};

pm.fn.extend(
{
    edit: function(_submit, _custom)
    {
        this.edit = new pmEdit(this[0], _submit, _custom);
        return this;
    }
});
