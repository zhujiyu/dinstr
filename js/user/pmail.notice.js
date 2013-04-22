/*!
 * PMAIL项目 JavaScript v2.4.12
 *
 * 消息提示
 * @bref 消息提示 该文件依赖于 jquery.js
 * @author 朱继玉
 * @copyright @2012 公众邮件网
 */
pmNotice = function()
{
    this.title = document.title;
    this.ddNotice = $('.pm-manage-notice .pm-notice-content');
    this.ppNotice = $('.pm-notice-prompt .pm-notice-content');

    var _notice = this;
    $('.pm-manage-notice .pm-close-notice, .pm-notice-prompt .pm-close-notice').click(function()
    {
        _notice.clear();
        _notice.fans = _notice.channels = _notice.themes = _notice.approves = [];
        _notice.readed = 0;
        
        $('.pm-notice-prompt').hide();
        $('.pm-manage-notice .pm-notice-tip').remove();
    });
    
    return this;
};

pmNotice.fn = pmNotice.prototype = new pmBase();

pmNotice.fn.extend(
{
    title : '', readed: 0, msgCount: 0,
    fans : [], channels : [], themes : [], approves : [],

    addTip : function(_tip, _item, _str)
    {
        if( _tip.length === 0 )
        {
            _tip = _item.clone(true);
            if( $('.pm-notice-item', this.ppNotice).length < 5 )
                this.ppNotice.append(_tip).append('<div class="pm-border-line"></div>');
        }
        else
            _tip.html(_str);
    },

    getIds : function(_notices)
    {
        for (var _ids = '', i = 0; i < _notices.length; i ++ )
        {
            _ids += _notices[i].ID + ',';
        }
        return _ids.substr(0, _ids.length - 1);
    },
    
    fanTip : function()
    {
        var _item = $('.pm-notice-fan', this.ddNotice),
            _notices = 'notices=' + this.getIds(this.fans);

        if( _item.length === 0 )
        {
            _item = $('<div class="pm-notice-item"></div>').addClass('pm-notice-fan').attr('type', 'fan');
            if( $('.pm-notice-item', this.ddNotice).length < 5 )
                this.ddNotice.append(_item).append('<div class="pm-border-line"></div>');
        }

        if( this.fans.length === 1 )
            _item.html('<a href="user?id=' + this.fans[0].fan_user_id + '&' + _notices + '">' + this.fans[0].fan_username + '</a> 信任了你');
        else
            _item.html('<a href="ls?friend&' + _notices + '">' + this.fans.length + '个人信任了你</a>');

        var _noti = this;
        pm(_item).close(
        {
            onClose: function()
            {
                $('.pm-notice-fan').remove();
                _noti.drop(_noti.fans);
                _noti.fans = [];
            }
        });

        $('.pm-close', _item).attr('title', '知道了');
        if( this.ppNotice.length > 0 )
            this.addTip($('.pm-notice-fan', this.ppNotice), _item, _str);
    },

    channelTip : function(_idx)
    {
        var _str, _channel, _id = this.channels[_idx].ID,
            _notices = 'notices=' + this.getIds(this.channels[_idx].notices),
            _href = 'id=' + _id + '&' + _notices,
            _item = $('.pm-notice-channel#' + _id, this.ddNotice);

        if( _item.length === 0 )
        {
            _item = $('<div class="pm-notice-item"></div>').addClass('pm-notice-channel')
                .attr('id', _id).attr('type', 'channel');
            if( $('.pm-notice-item', this.ddNotice).length < 5 )
                this.ddNotice.append(_item).append('<div class="pm-border-line"></div>');
        }

        if( this.channels[_idx].notices.length === 1 )
        {
            if( this.channels[_idx].notices[0].apply_user_id === $('#userid').val() )
            {
                _channel = '<a href="chan?' + _href + '">' + this.channels[_idx].name + '</a>';
                if( this.channels[_idx].notices[0].status === 'refuse' )
                    _str = '你加入频道 ' + _channel + ' 的申请被拒绝。';
                else
                    _str = '你加入频道 ' + _channel + ' 的申请已经通过。';
            }
            else
            {
                _channel = '<a href="chan?applicant&' + _href + '">' + this.channels[_idx].name + '</a>';
                _str = '<a href="user?id=' + this.channels[_idx].notices[0].apply_user_id + '&' + _notices + '">'
                        + this.channels[_idx].notices[0].apply_username + '</a>' + ' 申请加入频道 ' + _channel;
            }
        }
        else
        {
            _channel = '<a href="chan?applicant&' + _href + '">' + this.channels[_idx].name + '</a>';
            _str = this.channels[_idx].notices.length + '项关于频道 ' + _channel;
        }
        _item.html(_str);

        var _noti = this, _cnotices = this.channels[_idx].notices;
        pm(_item).close(
        {
            onClose: function()
            {
                _noti.drop(_cnotices);
                $('.pm-notice-channel#' + _id).remove(   );
                for( var i = 0; i < _noti.channels.length; i ++ )
                {
                    if( _noti.channels[i].ID === _id )
                    {
                        _noti.channels.splice(i, 1);
                    }
                }
            }
        });

        $('.pm-close', _item).attr('title', '知道了');
        if( this.ppNotice.length > 0 )
            this.addTip($('.pm-notice-channel#' + _id, this.ppNotice), _item, _str);
    },

    themeTip : function(_idx)
    {
        var _id = this.themes[_idx].ID;
        var _theme = '<a href="mail?id=' + _id + '&notices='
            + this.getIds(this.themes[_idx].notices) + '">' + this.themes[_idx].content + '</a>';
        var _item = $('.pm-notice-theme#' + _id, this.ddNotice);
        var _str = this.themes[_idx].notices.length + '项关于邮件主题 ' + _theme + ' 。';

        if( _item.length === 0 )
        {
            _item = $('<div class="pm-notice-item"></div>').addClass('pm-notice-theme')
                    .attr('id', _id).attr('type', 'theme');
            if( $('.pm-notice-item', this.ddNotice).length < 5 )
                this.ddNotice.append(_item).append('<div class="pm-border-line"></div>');
        }

        if( this.themes[_idx].notices.length === 1 )
        {
            var _user = '<a href="user?id=' + this.themes[_idx].notices[0].mail_user_id + '">'
                + this.themes[_idx].notices[0].mail_username + '</a>';
            if( this.themes[_idx].notices[0].type === 'reply' )
                _str = ' 在话题 ' + _theme + ' 中回复了你';
            else if( this.themes[_idx].notices[0].type === 'mail' )
                _str = ' 回复了话题 ' + _theme;
            _str = _user + _str;
        }
        else
            _str = this.themes[_idx].notices.length + '项关于邮件主题 ' + _theme;
        _item.html(_str);

        var _noti = this, _notices = this.themes[_idx].notices;
        pm(_item).close(
        {
            onClose: function()
            {
                _noti.drop(_notices);
                $('.pm-notice-theme#' + _id).remove();
                for (var i = 0; i < _noti.themes.length; i ++ )
                {
                    if( _noti.themes[i].ID === _id )
                        _noti.themes.splice(i, 1);
                }
            }
        });

        $('.pm-close', _item).attr('title', '知道了');
        if( this.ppNotice.length > 0 )
            this.addTip($('.pm-notice-theme#' + _id, this.ppNotice), _item, _str);
    },

    approveTip : function(_idx)
    {
        var _id = this.approves[_idx].ID;
        var _theme = '<a href="mail?approval&id=' + _id + '&notices='
            + this.getIds(this.approves[_idx].notices) + '">' + this.approves[_idx].content + '</a>';
        var _item = $('.pm-notice-approve#' + _id, this.ddNotice);
        var _str = this.approves[_idx].notices.length + '人支持了主题 ' + _theme;

        if( _item.length === 0 )
        {
            _item = $('<div class="pm-notice-item"></div>').addClass('pm-notice-approve')
                    .attr('id', _id).attr('type', 'approve');
            if( $('.pm-notice-item', this.ddNotice).length < 5 )
                this.ddNotice.append($('<div class="pm-border-line"></div>')).append(_item);
        }

        if( this.approves[_idx].notices.length === 1 )
        {
            var _user = '<a href="user?id=' + this.approves[_idx].notices[0].approve_user_id + '">'
                + this.approves[_idx].notices[0].approve_username + '</a>';
            _str = _user + '支持了主题 ' + _theme;
        }
        _item.html(_str);

        var _noti = this, _notices = this.approves[_idx].notices;
        pm(_item).close(
        {
            onClose: function()
            {
                _noti.drop(_notices);
                $('.pm-notice-approve#' + _id).remove(  );
                for (var i = 0; i < _noti.approves.length; i ++ )
                {
                    if( _noti.approves[i].ID === _id )
                        _noti.approves.splice(i, 1);
                }
            }
        });

        $('.pm-close', _item).attr('title', '知道了');
        if( this.ppNotice.length > 0 )
            this.addTip($('.pm-notice-approve#' + _id, this.ppNotice), _item, _str);
    },

    countTip : function(_count, _navi)
    {
        var _nTip = $('.pm-notice-count', _navi);

        if( _count > 0 )
        {
            if( _nTip.length === 0 )
            {
                _nTip = $('<span class="pm-notice-count pm-corner-all"></span>').appendTo(_navi);
            }
            _nTip.text(_count);
        }
        else if( _nTip.length > 0 )
        {
            _nTip.hide();
        }
    },
    
    merge : function(_notice)
    {
        var i, idx;
        if( _notice.type === 'mail' || _notice.type === 'reply' )
        {
            for ( i = 0, idx = this.themes.length; i < this.themes.length; i ++ )
            {
                if( this.themes[i].ID === _notice.theme_id )
                {
                    idx = i;
                    break;
                }
            }

            if( idx === this.themes.length )
            {
                this.themes[idx] = {};
                this.themes[idx].ID = _notice.theme_id;
                this.themes[idx].content = _notice.theme;
                this.themes[idx].notices = [_notice];
            }
            else
                this.themes[i].notices.push(_notice);
        }
        else if( _notice.type === 'approve' )
        {
            for( i = 0, idx = this.approves.length; i < this.approves.length; i ++ )
            {
                if( this.approves[i].ID === _notice.theme_id )
                {
                    idx = i;
                    break;
                }
            }

            if( idx === this.approves.length )
            {
                this.approves[idx] = {};
                this.approves[idx].ID = _notice.theme_id;
                this.approves[idx].content = _notice.theme;
                this.approves[idx].notices = [_notice];
            }
            else
                this.approves[i].notices.push(_notice);
        }
        else if( _notice.type === 'apply' )
        {
            for( i = 0, idx = this.channels.length; i < this.channels.length; i ++ )
            {
                if( this.channels[i].ID === _notice.channel_id )
                {
                    idx = i;
                    break;
                }
            }

            if( idx === this.channels.length )
            {
                this.channels[idx] = {};
                this.channels[idx].ID = _notice.channel_id;
                this.channels[idx].name = _notice.channel;
                this.channels[idx].notices = [_notice];
            }
            else
                this.channels[i].notices.push(_notice);
        }
        else if( _notice.type === 'fan' )
        {
            this.fans.push(_notice);
        }
    },

    process : function(data)
    {
        if( data.err )
        {
            pm('<div>').tip({content: data.err});
            return;
        }

        var i, j, _count;
        this.readed += data.notices.length;
        this.msgCount = data.msg;

        for( i = 0; i < data.notices.length; i ++ )
            this.merge(data.notices[i]);
        if( this.fans.length > 0 )
            this.fanTip();
        for( i = this.channels.length - 1; i >= 0; i -- )
            this.channelTip(i);
        for( i = this.approves.length - 1; i >= 0; i -- )
            this.approveTip(i);
        for( i = this.themes.length - 1; i >= 0; i -- )
            this.themeTip(i);

        this.countTip(this.channels.length, $('.pm-content-navi .channel'));
        this.countTip(this.fans.length, $('.pm-content-navi .friend'));

        for( i = 0, _count = 0; i < this.themes.length; i ++ )
        {
            for( j = 0; j < this.themes[i].notices.length; j ++ )
            {
                if( this.themes[i].notices[j].type === 'reply' )
                {
                    _count ++;
                    break;
                }
            }
        }
        this.countTip(_count, $('.pm-content-navi .reply'));

        for( i = 0, _count = 0; i < this.themes.length; i ++ )
        {
            for( j = 0; j < this.themes[i].notices.length; j ++ )
            {
                if( this.themes[i].notices[j].type === 'mail' )
                {
                    _count ++;
                    break;
                }
            }
        }
        this.countTip(_count, $('.pm-content-navi .interest'));

        var _prompt = $('.pm-notice-prompt');
        var _ntip = $('.pm-manage-notice .pm-notice-tip');
        _count = this.themes.length + this.channels.length + this.approves.length + this.fans.length;

        if( _count > 0 )
        {
            if( _ntip.length === 0 )
                _ntip = $('<span class="pm-notice-tip"><span class="pm-notice-count pm-corner-all"></span></span>').appendTo($('.pm-manage-notice'));
            $('.pm-notice-count', _ntip).text(_count);
            _prompt.show();
            $('.pm-manage-notice .pm-dropdown-list').addClass('pm-notice-list');
            document.title = '(' + _count + ')' + this.title;

            if( _count > 5 )
                $('.pm-ctrl', _prompt).show();
            else
                $('.pm-ctrl', _prompt).hide();
        }
        else if( _ntip.length > 0 )
        {
            _prompt.hide();
            _ntip.hide();
            $('.pm-manage-notice .pm-dropdown-list').removeClass('pm-notice-list');
            document.title = this.title;
        }

        _ntip = $('.pm-manage-account .pm-notice-tip');
        if( this.msgCount > 0 )
        {
            if( _ntip.length === 0 )
                _ntip = $('<span class="pm-notice-tip"><span class="pm-icon-mail"></span></span>').appendTo($('.pm-manage-account'));
            if( $('.pm-manage-account .pm-user-message .pm-notice-count').length < 1 )
                $('.pm-manage-account .pm-user-message a').append($('<span class="pm-notice-count pm-corner-all"></span>'));
            $('.pm-manage-account .pm-user-message .pm-notice-count').text(this.msgCount);
        }
        else if( _ntip.length > 0 )
        {
            _ntip.hide();
        }
        
        var _notice = this;
        setTimeout(function()
        {
            _notice.refrush();
        }, 90000);
    }, 
    
    start: function()
    {
        var _notice = this;

        $.get('api/pmail.api.notice.php',
        {
            p: 'init'
        },

        function(data)
        {
            _notice.process(data);
        }, 'json');
    },

    refrush: function()
    {
        var _notice = this;

        $.get('api/pmail.api.notice.php',
        {
            p: 'refrush',
            readed: this.readed
        },

        function(data)
        {
            _notice.process(data);
        }, 'json');
    },

    clear : function()
    {
        $.get('api/pmail.api.notice.php',
        {
            p: 'clear'
        },

        function(data)
        {
            if( data.err )
            {
                pm('<div>').tip({content: data.err});
                return;
            }
        }, 'json');
    },

    drop : function(_notices)
    {
        var _notice_ids = [];
        for(var j = 0; j < _notices.length; j ++ )
        {
            _notice_ids.push(_notices[j].ID);
        }
        
        $.get('api/pmail.api.notice.php',
        {
            p: 'drop',
            notice_ids: _notice_ids
        },

        function(data)
        {
            if( data.err )
            {
                pm('<div>').tip({content: data.err});
                return;
            }
        }, 'json');
    }
});

pmNotice.list = function(_list, _view)
{
    var _more = $('<div class="pm-page-more"><a id="more">更多</a></div>').attr('page', 1);
    _list.after(_more);

    $(_more).click(function()
    {
        var _page = parseInt(_more.attr('page'));
        
        $.get('api/pmail.api.load.php',
        {
            p: 'load-notice',
            item : _view,
            page : _page
        },

        function(data)
        {
            var _data = $(data);
            if( _data.hasClass('pm-err') )
            {
                pm('<div>').tip({content: data});
                return;
            }

            if( $('.pm-notice-item', _data).length > 0 )
            {
                _more.attr('page', (_page + 1));
                _data.removeAttr('class');
                _data.appendTo(_list);
            }
            else
            {
                _more.remove();
            }
        }, 'html');
    });
};

$(function()
{
    var _notice = new pmNotice();
    var _wrap = $('.pm-notice-wrap');
    
    _notice.start();
    $('.pm-fixed-notices').each(function()
    {
        pm($(this)).close(
        {
            onClose: function()
            {
                _wrap.remove();
            }
        });
        
        $('.pm-notice-item a', this).click(function()
        {
            var _item, _prnt = $(this).parent();

            if( _prnt.hasClass('pm-mail-notices') || _prnt.hasClass('pm-reply-notices') )
            {
                _item = $('.pm-mail[id=' + $(this).attr('id') + ']');
                pm(_item).prompt();
                window.scroll(0, _item.offset().top - 150);
            }

            return false;
        });
    });

    var _view = $('.pm-notice-page .pm-page-navi').attr('view');
    $('.pm-notice-page .pm-notice-list').each(function()
    {
        if( $('.pm-notice-item', this).length > 0 )
            pmNotice.list($(this), _view);
    });
});
