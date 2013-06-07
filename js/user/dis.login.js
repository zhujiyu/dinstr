/*!
 * PMAIL项目 JavaScript v2.4.12
 *
 * @bref 用户注册
 * @author 朱继玉
 * @copyright @2012 公众邮件网
 */
disRegis = function(_reg)
{
    var _form = $('.dis-register-form', _reg);
    var _veri = new disVeri(_form);

    _form.submit(function()
    {
        if( !_veri.emailChecked() || !_veri.pwordChecked() || !_veri.pword2Checked() || !_veri.unameChecked() )
            return false;
        return true;
    });

    $('#reset', _form).click(function()
    {
        $('input:reset', _form).click();
    });
    $('#register', _form).click(function()
    {
        _form.submit();
    });

    if( $('#email', _form).attr('readonly') )
    {
        $('.dis-veri', $('#email', _form).parents('.dis-veri-data')).show();
    }
};

disLogin = function(_form)
{
    var _pwrd = $('#pword', _form);
    var _pwpt = $('#pword-prompt', _form);
    var _desc = $('#uname', _form).val();

    _pwpt.focus(function()
    {
        _pwrd.show();
        _pwrd.focus();
        _pwpt.hide();
    });
    _pwrd.blur(function()
    {
        if( _pwrd.val() !== '' )
            return;
        _pwrd.hide();
        _pwpt.show();
    });
    _pwrd.bind('keyup', function(event)
    {
        if( event.keyCode === 13 )
        {
            _form.submit();
        }
    });
    
    pm('#uname', _form).promptText();
    
    var _name = $.cookie('dis-log-name');
    if( typeof _name !== 'undefined' && _name !== undefined )
    {
        $('input#uname', _form).val(_name);
    }
    $('#uname', _form).bind('keyup', function(event)
    {
        if( event.keyCode === 13 )
        {
            _pwpt.focus();
        }
    });
    
    $('.dis-login-submit', _form).click(function()
    {
        _form.submit();
    });
    
    _form.submit(function()
    {
        if( $('input#uname', this).val() === '' || $('input#uname', this).val() === _desc
            || $('input#pword', this).val() === '' )
        {
            pm('<div>').tip({mess: '\u8bf7\u8f93\u5165\u5b8c\u6574\u7684\u767b\u5f55\u4fe1\u606f\uff01'});
            return false;
        }

//        $('input:password#pword', this).val(md5($('input:password#pword', this).val()));
        $.cookie('dis-log-name', $('input#uname', this).val(), {expires: 30});
    });
};

$(function()
{
    $('.dis-user-register').each(function()
    {
        disRegis($(this));
    });
    $('.dis-login-form').each(function()
    {
        disLogin($(this));
    });
    alert(106);
    $('.dis-navi .dis-login').click(function()
    {
        if( $('.dis-login-form').length === 0 )
        {
//            var _form = $('<div><form class="dis-login-form" action="login" method="post"><div class="dis-user-login dis-dialog-content">\
//<input type="hidden" name="p" value="login"/>\
//<input class="dis-login-input" type="text" name="uname" id="uname" value="邮箱/用户名/用户ID"/>\
//<input class="dis-login-input" type="text" name="pword-prompt" id="pword-prompt" value="密码"/>\
//<input class="dis-login-input" type="password" name="pword" id="pword" value=""/>\
//</div>\
//<div class="dis-dialog-foot">\
//<input type="checkbox" name="autologin" checked id="autologin"><label for="autologin">自动登录</label></input>&nbsp;<a href="login?p=pword">忘记密码</a></span>\
//<input type="submit" style="display: none" value="登录"></input>\
//<span class="dis-login-button"><a class="dis-gray-button" id="cancel">取消</a>&nbsp;<a class="dis-login-submit dis-light-button" id="ok">登录</a></span>\
//</div></form></div>');
            var _form = $('<div><form class="dis-login-form" action="login" method="post"><div class="dis-user-login dis-dialog-content">\
<input type="hidden" name="p" value="login"/>\
<input class="dis-login-input" type="text" name="uname" id="uname" value="邮箱/用户名/用户ID"/>\
<input class="dis-login-input" type="text" name="pword-prompt" id="pword-prompt" value="密码"/>\
<input class="dis-login-input" type="password" name="pword" id="pword" value=""/>\
<input type="checkbox" name="autologin" checked id="autologin"><label for="autologin">自动登录</label></input>&nbsp;<a href="login?p=pword">忘记密码</a></span>\
</div>\
<div class="dis-dialog-foot dis-ctrl">\
<input type="submit" style="display: none" value="登录"></input>\
<a class="dis-gray-button" id="cancel">取消</a>&nbsp;<a class="dis-login-submit dis-light-button" id="ok">登录</a>\
</div></form></div>');
            var _pDlg = pm(_form).dialog({modal: true});

            _form = $('.dis-login-form', _form);
            $('#ok', _form).unbind('click').click(function()
            {
                _form.submit();
                _pDlg.dialog('destroy');
            });
            
            $('#pword', _form).unbind('keyup').bind('keyup', function(event)
            {
                if( event.keyCode === 13 )
                {
                    _form.submit();
                    _pDlg.dialog('destroy');
                }
            });
            disLogin(_form);
        }
        else
            pm('.dis-login-form').prompt();
    });
    
    if( $('.dis-plugin').length )
    {
        var _pgn = $('.dis-plugin');
        pm('#email', _pgn).promptText();
        pm('#intro', _pgn).promptText();
        pm('.dis-success-prompt').close();

        $('#intro', _pgn).autoResize();
        $('.dis-ctrl #ok').click(function()
        {
            $(this).parents('form').submit();
            return false;
        });
    }
});
