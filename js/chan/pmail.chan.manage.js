/**
 * 管理channel会员的角色
 */
pm.accept = function()
{
    var _app = $(this).parents('.pm-applicant');
    var _chn = $(this).parents('.pm-channel-ctrl');

    $.get('api/pmail.api.channel.php',
    {
        p: 'manage',
        item: 'accept-apply',
        id: _chn.attr('id'),
        applicant_id: _app.attr('id'),
        user_id: _app.attr('user_id')
    },

    function(data)
    {
        if( data.msg )
        {
            pm('<div>').tip({content: data.msg});
            return;
        }
        _chn.html('\u5df2\u901a\u8fc7'); // 已通过
    }, 'json');
};

pm.refuse = function()
{
    var _app = $(this).parents('.pm-applicant');
    var _chn = $(this).parents('.pm-channel-ctrl');

    $.get('api/pmail.api.channel.php',
    {
        p: 'manage',
        item: 'refuse-apply',
        id: _chn.attr('id'),
        applicant_id: _app.attr('id'),
        user_id: _app.attr('user_id')
    },

    function(data)
    {
        if( data.msg )
        {
            pm('<div>').tip({content: data.msg});
            return;
        }
        _chn.html('\u88ab\u62d2\u7edd'); //被拒绝
    }, 'json');
};

pm.setEditor = function()
{
    var _mem = $(this).parents('.pm-member');
    var _chn = $(this).parents('.pm-channel-ctrl');

    $.get('api/pmail.api.channel.php',
    {
        p: 'manage',
        item: 'editor-role',
        id: _chn.attr('id'),
        user_id: _mem.attr('user_id')
    },

    function(data)
    {
        if( data.msg )
        {
            pm('<div>').tip({content: data.msg});
            return;
        }

        _chn.html('<div>管理员</div><a class="pm-role-member">取消管理权</a>');
        $('.pm-role-member', _chn).click(pm.cancelEditor);
    }, 'json');
};

pm.cancelEditor = function()
{
    var _mem = $(this).parents('.pm-member');
    var _chn = $(this).parents('.pm-channel-ctrl');

    if( _mem.attr('user_id') === $('#userid').val() )
    {
        var _dlg = $('<div><div class="pm-dialog-foot"><a id="cancel" class="pm-gray-button">取消</a><a id="ok" class="pm-light-button">确定</a></div></div>');
        pm(_dlg).tip({mess: '确实要取消自己的管理权吗？'});
        
        $('#ok', _dlg).click(function()
        {
            pm.cancelEditor.submit(_mem, _chn);
        });
    }
    else
        pm.cancelEditor.submit(_mem, _chn);
};

pm.cancelEditor.submit = function(_mem, _chn)
{
    $.get('api/pmail.api.channel.php',
    {
        p: 'manage',
        item: 'member-role',
        id: _chn.attr('id'),
        user_id: _mem.attr('user_id')
    },

    function(data)
    {
        if( data.msg )
        {
            pm('<div>').tip({content: data.msg});
            return;
        }

        _chn.html('<div>普通成员</div><a class="pm-role-editor">设为管理员</a>');
        $('.pm-role-editor', _chn).click(pm.setEditor);
    }, 'json');
};

pm.role =
{
    ctrl: $('<div class="pm-role-manage">\
            <div class="pm-role-item"><a class="pm-editor">主持人</a></div>\
            <div class="pm-role-item"><a class="pm-member">嘉宾</a></div>\
        </div>'),

    over: function()
    {
        $(this).append(pm.role.ctrl);
        pm.role.ctrl.show();
    },

    out: function()
    {
        pm.role.ctrl.hide();
    }
};

//pm.invite = function()
//{
//    var _dlg = $('<div class="pm-invite-dialog">'
//        + '<table class="pm-layout-table">'
//        + '<tr><td><label for="uname">用户名：</label></td><td id="invite"><div class="pm-border pm-inline-block"><input class="pm-no-border" name="uname" id="uname"></input></div></td></tr>'
//        + '<tr><td><label for="content">内容：</label></td><td><div class="pm-border"><textarea class="pm-no-border" name="content" id="content">我发现了一个适合你的频道，想邀请你加入。</textarea></div></td></tr>'
//        + '</table>'
//        + '<div class="pm-ctrl"><a id="cancel" class="pm-gray-button">取消</a><a id="ok" class="pm-light-button">确定</a></div>'
//        + '</div>').attr('id', $(this).parents('.pm-channel-ctrl').attr('id'));
//    var ctrl = $('.pm-ctrl', _dlg);
//
//    pm(_dlg).dialog({modal: true});
//    $('#ok', ctrl).click(pm.invite.submit);
//    $('#cancel', ctrl).click(function(){pm(_dlg).dialog('destroy'); });
//};
//
//pm.invite.submit = function()
//{
//    var _dlg = $(this).parents('.pm-invite-dialog');
//    var _email = $('#email', _dlg).val();
//    var _uname = $('#uname', _dlg).val();
//    var _reg = /^(\w+[-|\.]?)+\w@(\w+\.)+[a-z]{2,}$/i;
//
//    $('#invite .pm-err-text', _dlg).remove();
//    if( _email !== undefined && _email === '' )
//    {
//        $('#invite', _dlg).append('<div class="pm-err-text pm-inline-block">请输入邮箱地址</div>');
//        return false;
//    }
//    if( _uname !== undefined && _uname === '' )
//    {
//        $('#invite', _dlg).append('<div class="pm-err-text pm-inline-block">请输入用户名</div>');
//        return false;
//    }
//    if( _email !== undefined && !_reg.exec(_email) )
//    {
//        $('#invite', _dlg).append('<div class="pm-err-text pm-inline-block">邮箱地址格式不正确</div>');
//        return false;
//    }
//
//    $.get('api/pmail.api.channel.php',
//    {
//        p: 'invite',
//        id: _dlg.attr('id'),
//        uname: _uname,
//        email: _email,
//        content: $('#content', _dlg).val()
//    },
//
//    function(data)
//    {
//        if( data.msg )
//        {
//            $('#invite', _dlg).append('<div class="pm-err-text pm-inline-block">' + data.msg + '</div>');
//            return;
//        }
//        
//        pm(_dlg).dialog('destroy');
//    }, 'json');
//    return false;
//};
//
//pm.invite.email = function()
//{
//    var _dlg = $('<div class="pm-invite-dialog">'
//        + '<table class="pm-layout-table">'
//        + '<tr><td><label for="email">邮箱：</label></td><td id="invite"><div class="pm-border pm-inline-block"><input class="pm-no-border" name="email" id="email"></input></div></td></tr>'
//        + '<tr><td><label for="content">内容：</label></td><td><div class="pm-border"><textarea class="pm-no-border" name="content" id="content">我发现了一个适合你的频道，想邀请你加入。</textarea></div></td></tr>'
//        + '</table>'
//        + '<div class="pm-ctrl"><a id="cancel" class="pm-gray-button">取消</a><a id="ok" class="pm-light-button">确定</a></div>'
//        + '</div>').attr('id', $(this).parents('.pm-channel-ctrl').attr('id'));
//    var ctrl = $('.pm-ctrl', _dlg);
//
//    pm(_dlg).dialog({modal: true});
//    $('#ok', ctrl).click(pm.invite.submit);
//    $('#cancel', ctrl).click(function(){pm(_dlg).dialog('destroy'); });
//};

pm.manage = function(_chan)
{
    $('.pm-apply-accept', _chan).click(pm.accept);
    $('.pm-apply-refuse', _chan).click(pm.refuse);
    $('.pm-role-editor', _chan).click(pm.setEditor);
    $('.pm-role-member', _chan).click(pm.cancelEditor);
    $('.pm-change-role', _chan).hover(pm.role.over, pm.role.out);
//    $('.pm-invite-web', _chan).click(pm.invite);
//    $('.pm-invite-email', _chan).click(pm.invite.email);
};

pm.announce = function()
{
    var _adv = $(this).parents('.pm-channel-announce');
    var _txt = $('<div class="pm-announce-edit"><textarea></textarea></div>').prependTo(_adv);
    var _can = $('<a class="pm-gray-button" id="cancel">取消</a>');
    var _sav = $('<a class="pm-light-button" id="save">保存</a>');

    $('.pm-ctrl', _adv).append(_can).append(_sav);
    $('.announce', _adv).hide();
    $(this).hide();
    $('textarea', _txt).text($('.announce', _adv).html()).autoResize();

    _can.click(function()
    {
        _txt.remove();
        $('.announce', _adv).show();
        $('.pm-ctrl #cancel', _adv).remove();
        $('.pm-ctrl #save', _adv).remove();
        $('.pm-ctrl .pm-edit', _adv).show();
    });

    _sav.click(function()
    {
        var _chn = $(this).parents('.pm-channel-ctrl');
        var _txt = $('.pm-announce-edit', _adv);

        $.get('api/pmail.api.channel.php',
        {
            p: 'edit',
            view: 'announce',
            id: _chn.attr('id'),
            announce: $('textarea', _txt).val()
        },

        function(data)
        {
            if( data.msg )
            {
                pm('<div>').tip({content: data.msg}); return;
            }

            $('.announce', _adv).show().html($('textarea', _txt).val());
            _txt.remove();
            $('.pm-ctrl #cancel', _adv).remove();
            $('.pm-ctrl #save', _adv).remove();
            $('.pm-ctrl .pm-edit', _adv).show();
        }, 'json');
    });
};

$(function()
{
    pm.manage($('.pm-channel-ctrl'));
    $('.pm-channel-announce .pm-edit').click(pm.announce);
});
