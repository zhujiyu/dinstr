/*!
 * PMAIL项目 JavaScript v2.4.12
 * 私信
 * @bref 私信
 * @author 朱继玉
 * @copyright @2012 公众邮件网
 */
pmMessage = function(_mess)
{
    $('.pm-ctrl #reply', _mess).unbind('click').click(pmMessage.reply);
    $('.pm-ctrl #delete', _mess).unbind('click').click(pmMessage.remove);
};

pmMessage.reply = function()
{
    var _mess = $(this).parents('.pm-message');
    var _edit = $(_mess).next();

    if( _edit.hasClass('pm-message-reply') )
    {
        _edit.remove();
        return;
    }

    _edit = $('<div class="pm-message-edit" title="发送私信">\
<div class="pm-message-relation pm-option"><input type="text" name="relation" id="relation"></input></div>\
<div class="pm-message-reciever pm-option"><input type="text" name="reciever" id="reciever"></input></div>\
<div class="pm-message-content"><textarea name="content" id="content"></textarea></div>\
<div class="pm-ctrl"><a id="cancel" class="pm-gray-button">取消</a><a id="send" class="pm-light-button ui-corner-all">发送</a></div>\
</div>').addClass('pm-message-reply pm-specail');
    _mess.after(_edit);

    if( !_mess.hasClass('pm-message-user') )
    {
        $('.pm-message-reciever input', _edit).val(_mess.parents('.pm-message-list').attr('friend'));
        $('.pm-message-relation input', _edit).val(_mess.parents('.pm-message-list').attr('relation'));
    }
    else
    {
        $('.pm-message-relation input', _edit).val(_mess.attr('relation'));
    }

    $('.pm-ctrl #cancel').click(function()
    {
        _edit.remove();
    });

    pm('.pm-message-content textarea', _edit).promptText();
    $('.pm-message-content textarea', _edit).autoResize();
    $('.pm-ctrl #send', _edit).click(pmMessage.edit.submit);
};

pmMessage.remove = function()
{
    var _mess = $(this).parents('.pm-message');
    var _dalg = $('<div class="pm-message-delete" title="删除私信">\
<div class="pm-dialog-content">确实要删除该条私信吗？</div>\
<div class="pm-dialog-foot pm-ctrl"><a class="pm-gray-button ui-corner-all" id="cancel">取消</a><a class="pm-light-button ui-corner-all" id="ok">确定</a></div>\
</div>');

    pm(_dalg).dialog({modal: true});
//    pmMessage.remove.mess = _mess;
//    pMail.Message.Delete.Mess = _mess;
    if( _mess.hasClass('pm-message-user') )
    {
        var _user = $('.pm-message-body .content #user', _mess);
        $('.pm-dialog-content', _dalg).text('确实要删除和' + _user.text() + '的私信吗？');
    }

    $('.pm-ctrl #ok', _dalg).click(function()
    {
//        var _mess = pMail.Message.Delete.Mess,
//            _relation = 0, _message = 0;
        var _relation = 0, _message = 0;

        if( !_mess.hasClass('pm-message-user') )
        {
            _relation = _mess.attr('relation');
            _message = _mess.attr('id');
        }
        else
        {
            _relation = _mess.parents('.pm-message-list').attr('relation');
        }

        $.get('api/pmail.api.message.php',
        {
            p: 'delete',
            relation: _relation,
            message: _message
        },

        function(data)
        {
            if( data.err )
            {
                pm('<div>').tip({mess: data.err});
                return;
            }

            _mess.fadeOut('slow', function()
            {
                $(this).next().remove();
                $(this).remove();
            });
//            _mess.next().remove();
//            _mess.remove();
            pm(_dalg).dialog('destroy');
            pm.loadImg();
        }, 'json');
    });

    $('.pm-ctrl #cancel', _dalg).click(function()
    {
        pm(_dalg).dialog('destroy');
    });
};

//pmMessage.remove.dialog = '<div class="pm-message-delete" title="删除私信">\
//<div class="pm-dialog-content">确实要删除该条私信吗？</div>\
//<div class="pm-dialog-foot"><div class="pm-ctrl"><a id="cancel">取消</a><a class="pm-light-button ui-corner-all" id="ok">确定</a></div></div>\
//</div>';

pmMessage.edit = function(_edit)
{
    pm('.pm-message-reciever input', _edit).promptText();
    pm('.pm-message-content textarea', _edit).promptText();
    $('.pm-message-content textarea', _edit).autoResize();
    $('.pm-ctrl #send', _edit).click(pmMessage.edit.submit);
};

pmMessage.edit.submit = function()
{
    var _edit = $(this).parents('.pm-message-edit');

    if( $('.pm-message-reciever input', _edit).val() === $('#username').val() )
    {
        pm('<div>').tip({mess: '请不要给自己发私信！'}); return;
    }
    if( $('.pm-message-content textarea', _edit).val() === '' )
    {
        pm('<div>').tip({mess: '不能发空信息！'}); return;
    }

    $.get('api/pmail.api.message.php', 
    {
        reciever: $('.pm-message-reciever input', _edit).val(),
        relation: $('.pm-message-relation input', _edit).val(),
        content: $('.pm-message-content textarea', _edit).val()
    },

    function(data)
    {
        if( data.err )
        {
            pm('<div>').tip({mess: data.err}); return;
        }

        pmMessage.add(data.message, data.friend);
        $('.pm-message-content textarea', _edit).val('');
        if( _edit.parents('.pm-message-list').length > 0 )
            _edit.remove();
    }, 'json');
};

pmMessage.add = function(message, friend)
{
    var _list = $('.pm-message-list'), _mess = $('<div class="pm-message">\
<div class="pm-message-avatar pm-inline-block"><div class="pm-avatar-img pm-avatar-middle pm-tile-img"></div></div>\
<div class="pm-message-body pm-inline-block"><div class="content"></div>\
<table class="pm-layout-table"><tr><td class="pm-time" time=""></td><td class="pm-ctrl"><a id="delete">删除</a></td></tr></table>\
</div></div>');

    if( _list.attr('type') === 'message-user-list' )
    {
        if( $('.pm-message-user[friend=' + friend.ID + ']', _list).length === 0 )
        {
//            _mess = $(pmMessage.item).addClass('pm-message-user');
            _mess.addClass('pm-message-user');
            _list.prepend($('<div class="pm-news-border"></div>')).prepend(_mess);
            $('.pm-avatar-img', _mess).attr('imgsrc', friend.avatar.small);
            $('.pm-ctrl', _mess).prepend('<a id="reply">回复</a> &nbsp;|&nbsp; ');
            $('.pm-ctrl', _mess).prepend('<a id="list"></a> &nbsp;|&nbsp; ');
            pm('.pm-avatar-img', _mess).loadImg();
        }
        else
            _mess = $('.pm-message-user[friend=' + friend.ID + ']', _list);

        $('.content', _mess).html('发送给<a id="user" href="user?id=' + friend.ID +'">'
            + friend.username + '</a>：' + message.message);
        if( message.new_message > 1 )
            $('.pm-ctrl #list', _mess).text('有' + (message.new_message - 1) + '条新私信');
        else
            $('.pm-ctrl #list', _mess).text('共' + message.message_num + '条私信');
    }
    else
    {
//        _mess = $(pmMessage.item);
        _list.prepend($('<div class="pm-news-border"></div>')).prepend(_mess);
        $('.pm-avatar-img', _mess).attr('imgsrc', $('#smallavatar').val());
        $('.content', _mess).html('我：' + message.message);
        pm('.pm-avatar-img', _mess).loadImg();
    }

    $('.pm-time', _mess).attr('time', message.create_time);
    pmMessage(_mess);
};

//pmMessage.item = '<div class="pm-message">\
//<div class="pm-message-avatar pm-inline-block"><div class="pm-avatar-img pm-avatar-middle pm-tile-img"></div></div>\
//<div class="pm-message-body pm-inline-block"><div class="content"></div>\
//<table class="pm-layout-table"><tr><td class="pm-time" time=""></td><td class="pm-ctrl"><a id="delete">删除</a></td></tr></table>\
//</div></div>';

//pmMessage.editCode ='<div class="pm-message-edit" title="发送私信">\
//<div class="pm-message-relation pm-option"><input type="text" name="relation" id="relation"></input></div>\
//<div class="pm-message-reciever pm-option"><input type="text" name="reciever" id="reciever"></input></div>\
//<div class="pm-message-content"><textarea name="content" id="content"></textarea></div>\
//<div class="pm-ctrl"><a id="cancel" class="pm-gray-button">取消</a><a id="send" class="pm-light-button ui-corner-all">发送</a></div>\
//</div>';

$(function()
{
    pmMessage($('.pm-message'));
    pmMessage.edit($('.pm-message-edit'));
});
