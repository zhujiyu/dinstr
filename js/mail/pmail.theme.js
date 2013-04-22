/*!
 * PMAIL项目 JavaScript v2.4.12
 *
 * @bref 需求操作的基础类
 * @author 朱继玉
 * @copyright @2012 公众邮件网
 */
pmTheme = function(theme)
{
    this.theme = $(theme);
    $('.pm-interest', this.theme).click(pmTheme.interest);
    $('.pm-interest-cancel', this.theme).click(pmTheme.cancelInterest);
    $('.pm-approve', this.theme).click(pmTheme.approve);
    $('.pm-approve-cancel', this.theme).click(pmTheme.cancelApprove);
    return this;
};

pmTheme.btns =
{
    cancelInterest : '<span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-pin-w"></span></span>&nbsp;取消关注',
    interest : '<span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-pin-s"></span></span>&nbsp;关注',
    cancelApprove : '<span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-cancel"></span></span>&nbsp;取消参与',
    approve : '<span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-heart"></span></span>&nbsp;参与'
};

pmTheme.interest = function()
{
    var theme = $(this).parents('.pm-theme');
    var _btn = $('.pm-interest', theme);

    $.get('api/pmail.api.theme.php',
    {
        p: 'interest',
        theme_id: theme.attr('id'),
        encode: 'json'
    },

    function(data)
    {
        if( data.err )
        {
            pm('<div>').tip({content: data.err}); return;
        }

        _btn.unbind('click').click(pmTheme.cancelInterest);
        if( _btn.hasClass('pm-light-button') )
            _btn.removeClass('pm-light-button').addClass('pm-gray-button');
        if( $('.pm-icon', _btn).length > 0 )
            _btn.html(pmTheme.btns.cancelInterest);
        else
            _btn.html('取消关注');
//            $('.pm-icon', _btn).removeClass('ui-icon-pin-s').addClass('ui-icon-pin-w');
        _btn.removeClass('pm-interest').addClass('pm-interest-cancel');
    }, 'json');
};

pmTheme.cancelInterest = function()
{
    var theme = $(this).parents('.pm-theme');
    var _btn = $('.pm-interest-cancel', theme);

    $.get('api/pmail.api.theme.php',
    {
        p: 'cancel-interest',
        theme_id: theme.attr('id'),
        encode: 'json'
    },

    function(data)
    {
        if( data.err )
        {
            pm('<div>').tip({content: data.err}); return;
        }

        _btn.unbind('click').click(pmTheme.interest);
        if( _btn.hasClass('pm-gray-button') )
            _btn.removeClass('pm-gray-button').addClass('pm-light-button');
        if( $('.pm-icon', _btn).length > 0 )
            _btn.html(pmTheme.btns.interest);
        else
            _btn.html('关注');
        _btn.removeClass('pm-interest-cancel').addClass('pm-interest');

        if( $('.pm-approve-cancel', theme).length > 0 )
        {
            $('.pm-approve-cancel', theme).html(pmTheme.btns.approve).unbind('click')
                .removeClass('pm-approve-cancel').addClass('pm-approve')
                .removeClass('pm-gray-button').addClass('pm-light-button')
                .click(pmTheme.approve);
        }
//        $('<a>').html(pmTheme.btns.approve);
    }, 'json');
};

pmTheme.approve = function()
{
    var theme = $(this).parents('.pm-theme');
    var _btn = $('.pm-approve', theme);

    $.get('api/pmail.api.theme.php',
    {
        p: 'approve',
        theme_id: theme.attr('id'),
        encode: 'json'
    },

    function(data)
    {
        if( data.err )
        {
            pm('<div>').tip({content: data.err}); return;
        }

        _btn.unbind('click').click(pmTheme.cancelApprove);
        if( _btn.hasClass('pm-light-button') )
            _btn.removeClass('pm-light-button').addClass('pm-gray-button');
        if( $('.pm-icon', _btn).length > 0 )
            _btn.html(pmTheme.btns.cancelApprove);
        else
            _btn.html('取消参与');
        _btn.removeClass('pm-approve').addClass('pm-approve-cancel');

        if( $('.pm-interest', theme).length > 0 )
        {
            _btn = $('.pm-interest', theme);
            _btn.unbind('click').click(pmTheme.cancelInterest);
            if( _btn.hasClass('pm-light-button') )
                _btn.removeClass('pm-light-button').addClass('pm-gray-button');
            if( $('.pm-icon', _btn).length > 0 )
                _btn.html(pmTheme.btns.cancelInterest);
            else
                _btn.html('取消关注');
            _btn.removeClass('pm-interest').addClass('pm-interest-cancel');
        }
    }, 'json');
};

pmTheme.cancelApprove = function()
{
    var theme = $(this).parents('.pm-theme');
    var _btn = $('.pm-approve-cancel', theme);

    $.get('api/pmail.api.theme.php',
    {
        p: 'cancel-approve',
        theme_id: theme.attr('id'),
        encode: 'json'
    },

    function(data)
    {
        if( data.err )
        {
            pm('<div>').tip({content: data.err}); return;
        }

        _btn.unbind('click').click(pmTheme.approve);
        if( _btn.hasClass('pm-gray-button') )
            _btn.removeClass('pm-gray-button').addClass('pm-light-button');
        if( $('.pm-icon', _btn).length > 0 )
            _btn.html(pmTheme.btns.approve);
        else
            _btn.html('参与');
        _btn.removeClass('pm-approve-cancel').addClass('pm-approve');
    }, 'json');
};

_pMail.fn.extend(
{
    theme : function()
    {
        var _theme = this;
        this.themes = new Array();

        if( this[0].hasClass('pm-theme') )
        {
            $(this[0]).each(function()
            {
                _theme.themes.push(new pmTheme(this));
            });
        }
        else
        {
            $('.pm-theme', this[0]).each(function()
            {
                _theme.themes.push(new pmTheme(this));
            });
        }
//        this.theme = new pmTheme($(this[0]));
        return this;
    }
});

$(function()
{
    pm('.pm-theme').theme();

    $('.pm-theme-list').each(function()
    {
        if( !$(this).hasClass('pm-more-list') )
            return ;
        
        pm($(this)).list(
        {
            more : function(_mails)
            {
                _mails.before('<div class="pm-content-border"></div>');
                pm(_mails).mail().theme();
            },
            
            count : 20,
            object : '.pm-theme',
            item : $(this).attr('item')
        });
    });
});

//pmTheme.deadline = function(theme)
//{
//    if( !$(theme).hasClass('pm-theme') )
//        return ;
//
//    var _now = new Date();
//    var _time = $('.pm-theme-period', theme);
//    var _time_stamp = _time.attr('deadline') * 1000 - _now.getTime();
//    var _sec = Math.floor(_time_stamp / 1000);
//    var _day = Math.floor(_sec / 86400);
//    var _hour = Math.floor((_sec - _day * 86400) / 3600);
//    var _min = Math.floor((_sec - _day * 86400 - _hour * 3660) / 60);
//    _time.text(_day + '天' + _hour + '小时' + _min + "分");
//};

//        var _cbtn = $(pmTheme.btns.cancelInterest);
//        _btn.before(_cbtn);
//        _btn.remove();
//        _cbtn.click(pmTheme.cancelInterest);
        
//        var _cbtn = $(pmTheme.btns.interest);
//        _btn.before(_cbtn);
//        _btn.remove();
//        _cbtn.click(pmTheme.interest);
