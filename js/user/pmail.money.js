/*!
 * PMAIL项目 JavaScript v2.4.12
 *
 * @bref 积分管理
 * @author 朱继玉
 * @copyright @2012 公众邮件网
 */
$(function()
{
    $('.pm-imoney-ctrl .pm-get-money').click(function()
    {
        var _dalg = $('<div class="pm-imagecode-ctrl">\
<div class="pm-dialog-content">\
<p class="pm-code-image">\
<img src="api/pmail.api.imagecode.php">&nbsp;&nbsp;<a id="recode">看不清，换一张</a>\
</p>\
为了确认您的身份，请输入上图中的验证码。\
<div>\
<input type="text" name="imagecode" id="imagecode" class="pm-border"></input>&nbsp;\
<span class="pm-veri-data">\
<span class="pm-veri pm-icon-wrap"><span class="pm-icon ui-icon-check"></span></span><label class="pm-desc">验证码错误</label></span>\
</div>\
</div>\
<div class="pm-dialog-foot pm-ctrl"><a class="pm-gray-button" id="cancel">取消</a><a class="pm-light-button" id="ok">确定</a></div>\
</div>');

        pm(_dalg).dialog({modal: true, title: '请输入验证码'});
        $('.pm-veri', _dalg).hide();
        $('.pm-desc', _dalg).hide();

        $('a#recode', _dalg).click(function()
        {
            $('.pm-code-image img', _dalg).attr('src', 'api/pmail.api.imagecode.php#' + Math.random());
        });

        $('a#ok', _dalg).unbind('click').click(function()
        {
            $.get('api/pmail.api.user.php',
            {
                p : 'imoney',
                code : $('#imagecode', _dalg).val()
            },

            function(data)
            {
                if( data.msg )
                {
                    pm('<div>').tip({content: data.msg});
                    return;
                }
                else if( data.code_err )
                {
                    $('.pm-desc', _dalg).show().text(data.code_err);
                    return;
                }
                else if( typeof data.imoney_err === 'undefined' )
                {
                    $('.pm-veri', _dalg).show();
                    $('.pm-user-param .pm-imoney').text(data.imoney);
                }

                pm(_dalg).dialog('destroy');
                $('.pm-imoney-ctrl').parent().remove();

                if( data.imoney_err )
                {
                    pm('<div>').tip({mess: data.imoney_err});
                }
            }, 'json');
        });

        $('#imagecode', _dalg).bind('keyup', function(event)
        {
            if( event.keyCode === 13 )
                $('a#ok', _dalg).click();
        });
    });

    pm('.pm-page-ctrl').close();
});
