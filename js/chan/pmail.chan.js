/*!
 * PMAIL项目 JavaScript v2.4.12
 *
 * 该文件依赖 pmail.core.js pmail.image.js ajaxuploadfile.js
 *
 * @bref 频道相关操作
 * @author 朱继玉
 * @copyright @2012 公众邮件网
 */
pm.chanBtns = 
{
    subscribe: '<a class="pm-subscribe pm-light-button"><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-pin-s"></span></span>&nbsp;订阅频道</a>',
    cancelSub: '<span class="pm-gray-button"><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-pin-w"></span></span>&nbsp;已订阅&nbsp;|&nbsp;<a class="pm-subscribe-cancel">取消</a></span>',
    join : '<a class="pm-join-apply pm-light-button"><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-volume-off"></span></span>&nbsp;申请加入</a>',
    cancelJoin: '<span class="pm-gray-button"><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-volume-on"></span></span>&nbsp;已加入&nbsp;|&nbsp;<a class="pm-quit">退出</a></span>'
};

pm.chanCache = [];

pm.subscribe = function()
{
    var _btn = $(this);
    var _chn = _btn.parents('.pm-channel-ctrl');

    $.get('api/pmail.api.channel.php',
    {
        p: 'user',
        item: 'subscribe',
        id: _chn.attr('id')
    },

    function(data)
    {
        if( data.msg )
        {
            pm('<div>').tip({content: data.msg});
            return;
        }

        if( _btn.hasClass('pm-light-button') )
            _btn.before($(pm.chanBtns.cancelSub)).remove();
        else
            _btn.text('取消订阅').unbind('click').removeClass('pm-subscribe').addClass('pm-subscribe-cancel');
        $('.pm-subscribe-cancel', _chn).click(pm.cancelSubscribe);
        pm.chanCache[_chn.attr('id')] = 0;
    }, 'json');
};

pm.cancelSubscribe = function()
{
    var _btn = $(this);
    var _chn = $(this).parents('.pm-channel-ctrl');

    $.get('api/pmail.api.channel.php',
    {
        p: 'user',
        item: 'cancel-subscribe',
        id: _chn.attr('id')
    },

    function(data)
    {
        if( data.msg )
        {
            pm('<div>').tip({content: data.msg}); return;
        }

        if( _btn.parent('.pm-gray-button').length > 0 )
            _btn.parent().before($(pm.chanBtns.subscribe)).remove();
        else
            _btn.text('订阅频道').unbind('click').removeClass('pm-subscribe-cancel').addClass('pm-subscribe');
        $('.pm-subscribe', _chn).click(pm.subscribe);
        pm.chanCache[_chn.attr('id')] = 0;
    }, 'json');
};

pm.apply = function()
{
    if( typeof $('#userId').val() === 'undefined' || $('#userId').val() === '' )
    {
        pm('<div>').tip({mess: '你尚未登录，请先登录。<br>如果没有账户，请先注册新账户。'});
        return;
    }
    
    var _dlg = $('<div class="pm-join-dialog" title="申请加入频道">\
<div class="pm-dialog-content"><div class="pm-border pm-apply-content"><textarea class="pm-no-border" name="content">验证信息</textarea></div></div>\
<div class="pm-dialog-foot pm-ctrl"><a id="cancel" class="pm-gray-button">取消</a><a id="ok" class="pm-light-button pm-publish">发表</a></div>\
</div>');
    var _cid = $(this).parents('.pm-channel-ctrl').attr('id');

    pm(_dlg).dialog({modal: true, title: '\u7533\u8bf7\u52a0\u5165\u9891\u9053'});
    pm('.pm-apply-content textarea', _dlg).promptText();
    $('.pm-apply-content textarea', _dlg).autoResize();

    $('#ok', _dlg).click(function()
    {
        $.get('api/pmail.api.channel.php',
        {
            p: 'user',
            item: 'join-apply',
            id: _cid,
            reason: $('.pm-apply-content textarea', _dlg).val()
        },

        function(data)
        {
            var _cont = data.msg  ? data.msg : '\u7533\u8bf7\u53d1\u9001\u6210\u529f\uff01';
            pm('<div>').tip({content: _cont});
        }, 'json');
    });
    
    return false;
};

pm.quit = function()
{
    var _btn = $(this);
    var _chn = _btn.parents('.pm-channel-ctrl');

    $.get('api/pmail.api.channel.php', 
    {
        p: 'user',
        item: 'quit',
        id: _chn.attr('id')
    },

    function(data)
    {
        if( data.msg )
        {
            pm('<div>').tip({content: data.msg}); return;
        }

        if( _btn.parent('.pm-gray-button').length > 0 )
            _btn.parent().before($(pm.chanBtns.join)).after($(pm.chanBtns.cancelSub)).remove();
        else
            _chn.html('<div class="role">已订阅</div><div class="manage"><a class="pm-apply-join">申请加入</a></div><div class="manage"><a class="pm-subscribe-cancel">取消订阅</a></div>');
        
        $('.pm-join', _chl).click(pm.apply);
        $('.pm-subscribe-cancel', _chn).click(pm.cancelSubscribe);
        pm.chanCache[_chn.attr('id')] = 0;
    }, 'json');
};

pm.fn.extend(
{
    channel : function()
    {
        $('.pm-subscribe', this[0]).click(pm.subscribe);
        $('.pm-subscribe-cancel', this[0]).click(pm.cancelSubscribe);
        $('.pm-apply-join', this[0]).click(pm.apply);
        $('.pm-quit', this[0]).click(pm.quit);
    }
});

$(function()
{
    pm('.pm-channel').channel();
    pm('.pm-page-right .pm-channel-ctrl').channel();
});
