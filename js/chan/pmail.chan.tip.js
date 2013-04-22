/*!
 * PMAIL项目 JavaScript v2.4.12
 *
 * 该文件依赖 pmail.core.js jquery.cookie.js
 *
 * @bref 频道相关操作
 * @author 朱继玉
 * @copyright @2012 公众邮件网
 */
pmTip = function(_tip, _holder)
{
    if( _tip.hasClass('pm-tip') )
    {
        this.cont = $('.pm-tip-content', _tip);
        this.wrap = _tip;
    }
    else
    {
        this.cont = _tip.addClass('pm-tip-content ui-corner-all');
        this.wrap = $('<div>').addClass('pm-tip').append(_tip)
            .append($('<div class="pm-shadow ui-widget-shadow"></div>')).prependTo($(document.body));

        this.wrap.hover(function()
        {
            $(this).addClass('pm-hover');
        },
        function()
        {
            $(this).removeClass('pm-hover');
        });
    }

    if( $('.pm-triangle', this.cont).length === 0 )
        this.cont.prepend($('<div class="pm-triangle"></div>'));
    this.holder = _holder;

    this.init();
    return this;
};

pmTip.fn = pmTip.prototype = new pmBase();

pmTip.fn.extend(
{
    direct : 'top',

    init: function()
    {
        if( document.documentElement.clientWidth * 2 / 3 < this.holder.offset().left )
            this.direct = 'left';
        else if( $(window).scrollTop() + document.documentElement.clientHeight / 2 < this.holder.offset().top )
            this.direct = 'top';
        else
            this.direct = 'bottom';
    },

    top: function()
    {
        this.wrap.css(
        {
            top : (this.holder.offset().top - this.cont.height() - 10) + 'px',
            left: (this.holder.offset().left - 20) + 'px'
        }).show();
        $('.pm-triangle', this.cont).removeAttr('class').addClass('pm-triangle pm-triangle-bottom').css({left: Math.max( Math.round(this.holder.width() / 2 + 10), 0 ) + 'px'});
    },

    bottom: function()
    {
        this.wrap.css(
        {
            top : (this.holder.offset().top + this.holder.height() + 5) + 'px',
            left: (this.holder.offset().left - 20) + 'px'
        }).show();
        $('.pm-triangle', this.cont).removeAttr('class').addClass('pm-triangle pm-triangle-top').css({left: Math.max( Math.round(this.holder.width() / 2 + 10), 0 ) + 'px'});
    },

    left: function()
    {
        this.wrap.show().css(
        {
            top : this.holder.offset().top + 'px',
            left: (this.holder.offset().left - this.cont.width() - 10) + 'px'
        });
        $('.pm-triangle', this.cont).removeAttr('class').addClass('pm-triangle pm-triangle-right');
    },

    right: function()
    {
        this.wrap.show().css(
        {
            top : this.holder.offset().top + 'px',
            left: (this.holder.offset().left + this.holder.width() + 10) + 'px'
        });
        $('.pm-triangle', this.cont).removeAttr('class').addClass('pm-triangle pm-triangle-left');
    },

    show: function()
    {
        if( this.direct === 'top' )
            this.top();
        else if( this.direct === 'left' )
            this.left();
        else if( this.direct === 'right' )
            this.right();
        else if( this.direct === 'bottom' )
            this.bottom();
        pm('.pm-channel-logo', this.cont).loadImg();
    }
});

if( typeof pm.chanBtns === 'undefined' )
    alert('本文件需要引用pmail.chan.js');

pm.chanTip = function(_chan, _tip)
{
    var _join, _subs,
        _info = $('.pm-channel-content', _tip), _ctrl = $('.pm-channel-ctrl', _tip).attr('id', _chan.ID);

    $('.pm-loading', _tip).remove();
    $('.pm-channel-logo', _info).attr('imgsrc', _chan.logo.small);
    $('.pm-channel-title', _info).html('<a href="chan?id=' + _chan.ID + '">' + _chan.name + '</a>');

    $('.pm-channel-param', _info).html('成员&nbsp;<a href="chan?id=' + _chan.ID + '&view=member">' + _chan.member_num + '</a>&nbsp;|&nbsp;'
        + '订阅&nbsp;<a href="chan?id=' + _chan.ID + '&view=subscriber">' + _chan.subscriber_num + '</a>&nbsp;|&nbsp;'
        + '邮件&nbsp;<a href="chan?id=' + _chan.ID + '&view=mail">' + _chan.mail_num + '</a>');
    $('.pm-channel-type', _info).html('<span class="pm-inline-block pm-icon-wrap">'
        + '<span class="pm-icon ui-icon-signal-diag"></span></span><span>' + pm.chanType(_chan.type) + '</span>&nbsp;&nbsp;');

    $('.pm-channel-tags', _info).empty();
    if( _chan.tags.length > 0 )
        $('.pm-channel-tags', _info).append($('<a href="chan?tag=' + _chan.tags[0].ID + '">' + _chan.tags[0].tag + '</a>'));
    else
        $('.pm-channel-tags', _info).remove();

    if( _chan.description )
    {
        var _desc = _chan.description;
        if( _desc.length > 40 )
            _desc = _desc.substr(0, 40)  + '...';
        $('.pm-channel-desc', _info).text(_desc);
    }
    else
        $('.pm-channel-desc', _info).text('没有介绍');

    for( var i = 1; i < _chan.tags.length; i ++ )
    {
        $('.pm-channel-tags', _info).append('&nbsp;|&nbsp;<a href="chan?tag=' + _chan.tags[i].ID + '">' + _chan.tags[i].tag + '</a>');
    }

    if( typeof _chan.status === "undefined" || _chan.status.role < 0 )
    {
        _join = $(pm.chanBtns.join).prependTo(_ctrl).click(pm.apply);
        _subs = $(pm.chanBtns.subscribe).appendTo(_ctrl).click(pm.subscribe);
    }
    else if( _chan.status.role === 0 )
    {
        _join = $(pm.chanBtns.join).prependTo(_ctrl).click(pm.apply);
        _subs = $(pm.chanBtns.cancelSub).appendTo(_ctrl);
        $('a', _subs).unbind('click').click(pm.cancelSubscribe);
    }
    else
    {
        _join = $(pm.chanBtns.cancelJoin).prependTo(_ctrl);
        $('a', _join).click(pm.quit);
    }
};

pmTip.fn.extend(
{
    display : function(_chan)
    {
        if( $('.pm-loading', this.cont).length > 0 )
            pm.chanTip(_chan, this.cont);
        this.show();
    }
});

pm.enterChan = function()
{
    var _chid = $(this).attr('id');
    if( _chid === undefined || _chid === '' )
        return;
    if( $('.pm-channel-tip').length > 0 )
        $('.pm-channel-tip').parents('.pm-tip').remove();

    var _chan = $('<div class="pm-channel-tip">\
<div class="pm-loading"><img src="css/images/loading.gif"/></div>\
<div class="pm-channel-content">\
<div class="pm-channel-logo pm-avatar-middle pm-tile-img pm-inline-block"><img /></div>\
<div class="pm-channel-info pm-inline-block"><div class="pm-channel-type"></div>\
<div class="pm-channel-title"></div><div class="pm-channel-param"></div><div class="pm-channel-tags"></div>\
</div>\
<div class="pm-channel-desc"></div>\
</div>\
<div class="pm-content-border"></div>\
<div class="pm-channel-ctrl pm-ctrl ui-corner-bottom"><a class="pm-add-mail">投稿</a></div>\
</div>').attr('id', _chid);
    var _ctip = new pmTip(_chan, $(this));

    if( pm.chanCache[_chid] )
    {
        _ctip.display(pm.chanCache[_chid]);
        return;
    }

    $.get('api/pmail.api.channel.php',
    {
        p: 'disp',
        id: _chid
    },

    function(data)
    {
        if( data.msg )
        {
            pm('<div>').tip({content: data.msg});
            return;
        }

        if( $('#userid').val() === '' && typeof pmCookie !== 'undefined' )
            data.channel.status = pmCookie().get(_chid);
        _ctip.display(data.channel);
        pm.chanCache[_chid] = data.channel;
    }, 'json');
};

pm.fn.extend(
{
    link : function()
    {
        var hoverTimer = 0, outTimer = 0;

        var _tipHide = function(_tip)
        {
            if( $(_tip).hasClass('pm-hover') )
            {
                outTimer = setTimeout(function()
                {
                    _tipHide(_tip);
                }, 200);
            }
            else
                $(_tip).hide();
        };

        $(this[0]).hover(function()
        {
            var _self = this;
            $(this).addClass('pm-hover');

            clearTimeout(outTimer);
            hoverTimer = setTimeout(function()
            {
                pm.enterChan.apply(_self);
            }, 500);
        },

        function()
        {
            var _tip = $('.pm-channel-tip#' + $(this).attr('id')).parents('.pm-tip');
            $(this).removeClass('pm-hover');

            clearTimeout(hoverTimer);
            outTimer = setTimeout(function()
            {
                _tipHide(_tip);
            }, 200);
        });
    }
});

$(function()
{
    pm('.pm-channel-link').link();
});
