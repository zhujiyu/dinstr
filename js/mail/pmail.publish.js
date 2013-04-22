/*!
 * PMAIL项目 JavaScript v2.4.12
 *
 * @bref 邮件管理类
 * @author 朱继玉
 * @copyright @2012 公众邮件网
 */
$(function()
{
    if( $('.pm-publish-mail').length > 0 )
    {
        pm('.pm-publish-mail').edit(
        {
            success : function()
            {
                self.location.href = 'home?feed';
            },

            check : function(_edit, _pedit)
            {
                var _data = _pedit.data;
                if( _data.title === _pedit.prompt.title || _data.title === '' )
                {
                    pm('<div>').tip({modal: true, mess: "请先填写邮件标题。"});
                    return false;
                }
                if( _data.channel_id === '0' || _data.channel_id === '' )
                {
                    pm('<div>').tip({modal: true, mess: "请至少添加一个邮件接受频道。"});
                    return false;
                }
                if( _data.content === _pedit.prompt.content )
                {
                    _data.content = _data.title;
                }
                return true;
            }
        });
    }

    if( $('.pm-theme-page').length > 0 && $('#userid').val() > 0 )
    {
        var _rply = $('<div class="pm-reply-form"></div>').prepend('<div><div class="pm-content-border"></div>\
<div class="pm-module-title" style="color:gray">添加回复</div>\
<div class="pm-content-border"></div></div>');

        $('.pm-theme-page .pm-mail-list').after(_rply);
        $('.pm-user-name', _rply).text($('#username').val());
        $('.pm-avatar-img img', _rply).attr('src', $('#smallavatar').val());

        pm(_rply).edit(
        {
            custom : function(_edit, _pedit)
            {
//                $('#p', _edit).val('reply');
                $('#parent', _edit).val($('.pm-theme-global').attr('id'));
                $('.pm-theme-title', _edit).remove();
                $('.pm-mail-target', _edit).before($('<a class="pm-util-tip">你还可以将该回复发送到频道</a>')).hide();

                $('.pm-util-tip', _edit).click(function()
                {
                    $(this).remove();
                    $('.pm-mail-target', _edit).show();
                });
            },

            check : function(_edit, _pedit)
            {
                var _data = _pedit.data;
                if( _data.content === _pedit.prompt.content || _data.content === '' )
                {
                    pm('<div>').tip({mess: "请先输入邮件内容！"});
                    return false;
                }
                return true;
            },

            success : function(data, _edit, _pedit)
            {
                alert(81);
                var _data = $(data).prependTo($('.pm-mail-list')).after('<div class="pm-content-border"></div>');
                $('.pm-theme', _data).remove();
                _pedit.complete();
                pm(_data).mail().prompt();
            }
        });
    }

//    var _str1 = '&nbsp;<div class="pm-mail-good pm-object" id="100026" numiid="16271766431" source="tmall"><div class="info"><span class="price">338元</span>：<a class="source" target="_blank" href="www.tmail.com">天猫商城</a><br><a class="title" target="_blank" href="http://detail.tmall.com/item.htm?spm=a220o.1000855.0.2.Vv2mSs&amp;id=16271766431&amp;scm=1003.3.03039.1&amp;acm=03039.1003.375.244.16271766431_1">派邦奴冬季新款休闲家居服 甜美可爱圆点珊瑚绒女士长袖睡袍大码</a></div><div class="photo"><img src="http://img02.taobaocdn.com/bao/uploaded/i2/T1SN21XdxaXXcEuQ3._082908.jpg"></div></div>';
//    var _str2 = '<div class="pm-mail-good pm-object" id="100026" numiid="16271766431" source="tmall"><div class="cart pm-corner-all"><img src="http://a.tbcdn.cn/p/mall/base/favicon2.ico"></div><div class="info"><span class="price">338元</span>：<a class="source" target="_blank" href="www.tmail.com">天猫商城</a><br><a class="title" target="_blank" href="http://detail.tmall.com/item.htm?spm=a220o.1000855.0.2.Vv2mSs&amp;id=16271766431&amp;scm=1003.3.03039.1&amp;acm=03039.1003.375.244.16271766431_1">派邦奴冬季新款休闲家居服 甜美可爱圆点珊瑚绒女士长袖睡袍大码</a></div><div class="photo"><img src="http://img02.taobaocdn.com/bao/uploaded/i2/T1SN21XdxaXXcEuQ3._082908.jpg"></div></div>';
//    var _str1 = '<br>自信在一段男女关系中相当于米饭，吃得太饱缺得太多都会让人难受，并且严重影响你的身体健康。<br>人要战胜因为经济而产生的自卑感，很难。&nbsp;<div class="pm-input-photo pm-object" id="100270"><div class="title">扎克伯格</div><div class="photo"><img src="attach/xw500/5D/2878c1c8b05dec6eb758d31d3a38cb"></div></div>&nbsp;<br>中等以上经济实力的人意味着他在社会中有一定的竞争力，基本上算是事业成功的人。在现代社会，事业成功绝大多数取决于个人能力（二世祖不在讨论范围内），能力强，眼界宽，见识广，自信越足，这对女性的引诱才是最大的。换言之，草包除了暴发户，是很难持续的「有钱」。';
//    var _str2 = '<br>&nbsp;<div class="pm-mail-good pm-object" id="100025" source="tmall" numiid="16342957316"><div class="info"><span class="price">498元</span>：<a class="source" target="_blank" href="www.tmail.com">天猫商城</a><br><a class="title" target="_blank" href="http://detail.tmall.com/item.htm?spm=a220m.1000858.1000725.6.Hzze5l&amp;id=16342957316&amp;is_b=1&amp;cat_id=2&amp;q=%C4%D0%CA%BF%CB%AF%C5%DB">派邦奴2012冬季新款家居服休闲格子浴袍长袖梭织夹棉男士睡袍</a></div><div class="photo"><img src="http://img02.taobaocdn.com/bao/uploaded/i2/T1etb2XkdhXXaCEAEW_024333.jpg"></div></div>&nbsp;';

//	$('.pm-mail-content #content').wysiwyg("clear");
//	$('.pm-mail-content #content').wysiwyg("insertHtml", '<b>下面的内容是自动加入的。</b>');
//	$('.pm-mail-content #content').wysiwyg("insertHtml", _str1);
//	$('.pm-mail-content #content').wysiwyg("insertHtml", _str2);
//	$('.pm-mail-content #content').wysiwyg("insertHtml", '下面的内容是自动加入的。</b><br>&nbsp;');
//
//    var _list = $('<div>' + $('.pm-mail-content #content').val() + '</div>');
//    $('.cart', _list).remove();
////    $('.pm-mail-content #content').val(_list.html());
//	$('.pm-mail-content #content').wysiwyg("clear");
//	$('.pm-mail-content #content').wysiwyg("insertHtml", _list.html());

//    $('.pm-mail-content #content').trigger('keydown');
//
//	alert( $('.pm-mail-content #content').wysiwyg("getContent") );
//    var _cont = $('.pm-mail-content #content').wysiwyg("getContent");
//    alert($('div', _cont).length);
//    _cont = $('<div>' + _cont + '</div>');
//    alert($('div.pm-object', _cont).length);
//	var Wysiwyg = $('.pm-mail-content #content').wysiwyg();
//    alert( typeof Wysiwyg.editorDoc);
//	$(Wysiwyg.editorDoc).trigger("editorRefresh.wysiwyg");
});

//$(function()
//{
//    if( $('.pm-publish-mail').length > 0 )
//    {
//        pm('.pm-publish-mail').edit(
//        {
//            success : function()
//            {
//        self.location.href = 'home?feed';
//            },
//
//            check : function(_edit, _pedit)
//            {
//                var _data = _pedit.data;
//    if( _data.title === _pedit.prompt.title || _data.title === '' )
//    {
//        pm('<div>').tip({modal: true, mess: "邮件必须要有标题。"});
//        return false;
//    }
//    if( _data.channel_id === undefined || _data.channel_id === null || _data.channel_id === '' )
//    {
//        pm('<div>').tip({modal: true, mess: "请至少添加一个邮件接受频道。"});
//        return false;
//    }
//    if( _data.content === _pedit.prompt.content )
//    {
//        _data.content = _data.title;
//    }
//
//            }
//        });
//    }
//
//    if( $('.pm-theme-page').length > 0 && $('#userid').val() > 0 )
//    {
//        var _rply = $('<div class="pm-reply-form"></div>').prepend('<div><div class="pm-content-border"></div>\
//<div class="pm-module-title" style="color:gray">添加回复</div>\
//<div class="pm-content-border"></div></div>');
//
////            .appendTo($('.pm-theme-page .pm-mail-list'));
//        $('.pm-theme-page .pm-mail-list').after(_rply);
//        $('.pm-user-name', _rply).text($('#username').val());
//        $('.pm-avatar-img img', _rply).attr('src', $('#smallavatar').val());
//
//        pm(_rply).edit(
//        {
//            custom : function(_edit, _pmedit)
//        {
//            $('#parent', _edit).val($('.pm-theme-global').attr('id'));
//            $('.pm-theme-title', _edit).remove();
//            $('.pm-mail-target', _edit).before($('<a class="pm-util-tip">你还可以将该回复发送到频道</a>')).hide();
//
//            $('.pm-util-tip', _edit).click(function()
//            {
//                $(this).remove();
//                $('.pm-mail-target', _edit).show();
//            });
//        },
//
//        check : function(_edit, _pedit)
//        {
//            var _data = _pedit.data;
//    if( _data.content === _pedit.prompt.content || _data.content === '' )
//    {
//        pm('<div>').tip({mess: "请输入邮件内容！"});
//        return false;
//    }
//        },
//
//        success : function(data, _edit, _pedit)
//        {
//        var _data = $(data).prependTo($('.pm-mail-list')).after('<div class="pm-content-border"></div>');
//        $('.pm-theme', _data).remove();
//        _pedit.complete();
//        pm(_data).mail().prompt();
//        }
//        });
//    }
//});

//pm.publish = function(_edit, _pmedit)
//{
//    if( _pmedit.title === _pmedit.prompt.title || _pmedit.title === '' )
//    {
//        pm('<div>').tip({modal: true, mess: "邮件必须要有标题。"});
//        return;
//    }
//    if( _pmedit.chan_id === undefined || _pmedit.chan_id === null || _pmedit.chan_id === '' )
//    {
//        pm('<div>').tip({modal: true, mess: "请至少添加一个邮件接受频道。"});
//        return;
//    }
//    if( _pmedit.content === _pmedit.prompt.content )
//    {
//        _pmedit.content = _pmedit.title;
//    }
//
//    $.post('api/pmail.api.mail.php',
//    {
//        p: 'publish',
//        channel_id: _pmedit.chan_id,
//        weight: _pmedit.weight,
//        title: _pmedit.title,
//        content: _pmedit.content,
//        goods: _pmedit.goods,
//        photos: _pmedit.photos
//    },
//
//    function(data)
//    {
//        if( $(data).hasClass('pm-err') )
//        {
//            pm('<div>').tip({content: data});
//            return;
//        }
//
//        self.location.href = 'home?feed';
//    }, 'html');
//};
//
//pm.suggest = function(_edit, _pmedit)
//{
//    if( _pmedit.content === _pmedit.prompt.content || _pmedit.content === '' )
//    {
//        pm('<div>').tip({mess: "请输入邮件内容！"});
//        return;
//    }
//
//    $.post('api/pmail.api.mail.php',
//    {
//        p: 'reply',
//        theme_id: $('#parent', _edit).val(),
//        channel_id: _pmedit.chan_id,
//        weight: _pmedit.weight,
//        content: _pmedit.content,
//        goods: _pmedit.goods,
//        photos: _pmedit.photos
//    },
//
//    function(data)
//    {
//        if( $(data).hasClass('pm-err') )
//        {
//            pm('<div>').tip({content: data});
//            return;
//        }
//
//        var _data = $(data).prependTo($('.pm-mail-list')).after('<div class="pm-content-border"></div>');
//        $('.pm-theme', _data).remove();
//        _pmedit.complete();
//        pm(_data).mail().prompt();
//    }, 'html');
//};
//
//$(function()
//{
//    if( $('.pm-publish-mail').length > 0 )
//    {
//        pm('.pm-publish-mail').edit(pm.publish);
//    }
//
//    if( $('.pm-theme-page').length > 0 && $('#userid').val() > 0 )
//    {
//        var _rply = $('<div class="pm-reply-form"></div>').prepend('<div><div class="pm-content-border"></div>\
//<div class="pm-module-title" style="color:gray">添加回复</div>\
//<div class="pm-content-border"></div></div>');
////            .appendTo($('.pm-theme-page .pm-mail-list'));
//        $('.pm-theme-page .pm-mail-list').after(_rply);
//        $('.pm-user-name', _rply).text($('#username').val());
//        $('.pm-avatar-img img', _rply).attr('src', $('#smallavatar').val());
//
//        pm(_rply).edit(pm.suggest, function(_edit, _pmedit)
//        {
//            $('#parent', _edit).val($('.pm-theme-global').attr('id'));
//            $('.pm-theme-title', _edit).remove();
//            $('.pm-mail-target', _edit).before($('<a class="pm-util-tip">你还可以将该回复发送到频道</a>')).hide();
//
//            $('.pm-util-tip', _edit).click(function()
//            {
//                $(this).remove();
//                $('.pm-mail-target', _edit).show();
//            });
//        });
//    }
//});

//        $('.pm-mail-ctrl #cancel', _edit).trigger('click');
//        window.scroll(0, $(_data).offset().top - 200);
//pm.replyUser = '<div><div class="pm-content-border"></div>\
//<div class="pm-module-title" style="color:gray">添加回复</div>\
//<div class="pm-content-border"></div></div>';
