/*!
 * PMAIL项目 JavaScript v2.4.12
 *
 * @bref 信息操作的基础类
 * @author 朱继玉
 * @copyright @2012 公众邮件网
 */
pmMail = function(_mail)
{
    _mail = $(_mail);
    if( !_mail.hasClass('pm-mail') )
        return this;

    this.mail = _mail;
    this.ID = _mail.attr('mail_id');
    this.content = $('.pm-mail-content', _mail).text();

    if( _mail.hasClass('pm-mail-processed') )
        return this;
    _mail.addClass('pm-mail-processed');

    if( typeof pmManage !== "undefined" )
    {
        $('.pm-mail-bottom .delete', _mail).unbind('click').click(pmManage.remove);
        $('.pm-mail-bottom .parent', _mail).unbind('click').click(pmManage.parents);
        $('.pm-mail-bottom .reply', _mail).unbind('click').click(pmManage.reply);
        $('.pm-mail-bottom .collect', _mail).unbind('click').click(pmManage.collect);
        $('.pm-mail-bottom .collected', _mail).unbind('click').click(pmManage.cancelCollect);
    }

    $('.pm-photo-img', _mail).each(function()
    {
        if( $(this).attr('smlimg') )
            $(this).attr('imgsrc', $(this).attr('smlimg')).addClass('pm-tile-img');
    });

//    $('.pm-mail-bottom .depth', _mail).each(pmMail.Depth);
    $('.pm-mail-bottom .depth', _mail).text(pmMail.Depth($('.pm-mail-bottom .depth', _mail).attr('depth')));
    $('.pm-photo .pm-photo-img, .pm-good-photo, .pm-good-cart, .pm-expan', _mail).click(pmMail.expan);
    $('.pm-expan', _mail).html(pmMail.btns.expan);
    pm('.pm-good', _mail).good();
    pm('.pm-load-display[imgsrc], .pm-photo-img', _mail).loadImg();
    return this;
};

pmMail.btns =
{
    expan : '显示全部 <span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-arrowthickstop-1-s"></span></span>',
    fold : '<span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-arrowthickstop-1-n"></span></span> 收起'
};

pmMail.depthNum = ['', '一', '二', '三', '四', '五', '六', '七', '八', '九'];
pmMail.depthWei = ['零', '楼', '十', '百', '千', '万'];

pmMail.Depth = function(_depth)
{
//    var str = $(this).attr('depth');
//    var _depth = '';
    var _res = '';
    var _len = _depth.length;

    if( _depth === '0' )
    {
//        $(this).html('顶楼');
        return '顶楼';
    }

    for( var i = 0; i < _len; i ++ )
    {
        if( _len === 2 && _depth[i] === '1' )
            _res += pmMail.depthWei[_len-i];
        else if( _len > 0 && _depth[i] === '0' )
        {
            if( _depth[i - 1] !== '0' )
                _res += pmMail.depthWei[0];
        }
        else
            _res += pmMail.depthNum[_depth[i]] + pmMail.depthWei[_len-i];
    }
    return _res;
//    $(this).text(_depth);
};

pmMail.expan = function()
{
    var _mail = $(this).parents('.pm-mail:first');
    var _cont = $(this).parents('.pm-mail-content:first').removeClass('pm-mail-content-small').addClass('pm-mail-content-big');

    $.get('api/pmail.api.mail.php',
    {
        p : 'load',
        item : 'whole',
        mail_id : $(this).parents('.pm-mail:first').attr('id')
    },

    function(data)
    {
        var _data = $(data);

        if( _data.hasClass('pm-err') )
        {
            pm('<div>').tip({content: data});
            return;
        }

        if( $('.pm-expan', _cont).length === 0 )
            $('.pm-mail-bottom', _mail).append('<a class="pm-expan"></a>');
        else
            $('.pm-mail-bottom', _mail).append($('.pm-expan', _cont));
        $('.pm-expan', _mail).html(pmMail.btns.fold).unbind('click').click(pmMail.fold);

        $('.pm-object-list', _cont).hide();
        $('.content', _cont).html(_data.html().replace(/[\n\r]/g, '<br>')).css({'white-space': 'pre-wrap'});
        pm('.pm-mail-good', _cont).good();
    }, 'html');
};

pmMail.fold = function()
{
    var _mail = $(this).parents('.pm-mail:first');
    var _cont = $('.pm-mail-content', _mail).removeClass('pm-mail-content-big').addClass('pm-mail-content-small');

    $('.content', _cont).html($('.content', _cont).text().replace(/<br>/g, '\n').slice(0, 150) + '...').css({'white-space': 'normal'});
    $('.pm-object-list', _cont).show();

    $('.pm-mail-content', _mail).append($('.pm-expan', _mail));
    $('.pm-expan', _mail).html(pmMail.btns.expan).unbind('click').click(pmMail.expan);
    window.scroll(0, Math.max(0, _cont.offset().top - 150));
};

pm.fn.extend(
{
    mail: function()
    {
        var _mail = this;
        this.mails = new Array();

        if( this[0].hasClass('pm-mail') )
        {
            $(this[0]).each(function()
            {
                _mail.mails.push(new pmMail(this));
            });
        }
        else
        {
            $('.pm-mail', this[0]).each(function()
            {
                _mail.mails.push(new pmMail(this));
            });
        }
        return this;
    }
});

$(function()
{
    pm('.pm-mail').mail();
//    alert(pmMail.Depth("18"));
//    alert(pmMail.Depth("101"));
//    alert(pmMail.Depth("2001"));
});
