/*!
 * PMAIL项目 JavaScript v2.4.12
 * @bref 用户
 * @author 朱继玉
 * @copyright @2012 公众邮件网
 */
pm.extend(
{
    follow : function()
    {
        var _btn = $(this);
        var _uid = $(this).parents('.pm-user-ctrl').attr('id');

        $.get('api/pmail.api.user.php',
        {
            p: 'follow', id: _uid
        },

        function(data)
        {
            if( data.msg )
            {
                pm('<div>').tip({content: data.msg});
                return;
            }

            if( _btn.hasClass('pm-light-button') )
            {
                var _span = $('<span class="pm-gray-button"></span>');
                if( $('.pm-icon', _btn).hasClass('ui-icon-plusthick') )
                    _span.html('<span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-arrowthick-1-w"></span></span>已信任&nbsp;|&nbsp;<a class="pm-follow-cancel">取消</a>');
                else
                    _span.html('<span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-transferthick-e-w"></span></span>相互信任注&nbsp;|&nbsp;<a class="pm-follow-cancel">取消</a>');
                _btn.replaceWith(_span);
                _btn = $('a', _span);
            }
            else
                _btn.text('取消信任');

            _btn.unbind('click').click(pm.cancelFollow);
            if( _btn.hasClass('pm-light-button') )
                _btn.removeClass('pm-light-button').addClass('pm-gray-button');
            _btn.removeClass('pm-follow').addClass('pm-follow-cancel');
        }, 'json');
    },
    
    cancelFollow : function()
    {
        var _btn = $(this);
        var _uid = $(this).parents('.pm-user-ctrl').attr('id');

        $.get('api/pmail.api.user.php',
        {
            p: 'cancel-follow', id: _uid
        },

        function(data)
        {
            if( data.msg )
            {
                pm('<div>').tip({content: data.msg});
                return;
            }

            if( _btn.hasClass('pm-gray-button') || _btn.parent().hasClass('pm-gray-button') )
            {
                _btn = _btn.parent().hasClass('pm-gray-button') ? _btn.parent() : _btn;
                var _a = $('<a class="pm-follow pm-light-button"></a>');
                if( $('.pm-icon', _btn).hasClass('ui-icon-transferthick-e-w') )
                    _a.html('<span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-arrowthick-1-e"></span></span>&nbsp;信任他');
                else
                    _a.html('<span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-plusthick"></span></span>&nbsp;信任他');
                _btn.replaceWith(_a);
                _btn = _a;
            }
            else
                _btn.text('信任他');

            _btn.unbind('click').click(pm.follow);
            if( _btn.hasClass('pm-gray-button') )
                _btn.removeClass('pm-gray-button').addClass('pm-light-button');
            _btn.removeClass('pm-follow-cancel').addClass('pm-follow');
        }, 'json');
    }
});

pm.fn.extend(
{
    user : function(_user)
    {
        $('.pm-follow', this[0]).click(pm.follow);
        $('.pm-follow-cancel', this[0]).click(pm.cancelFollow);
    }
});

$(function()
{
    pm('.pm-user-ctrl').user();
});

//pmUser = function(_user)
//{
//    $('.pm-follow', _user).click(pmUser.follow);
//    $('.pm-follow-cancel', _user).click(pmUser.cancelFollow);
//};
//
//pmUser.follow = function()
//{
//    var _btn = $(this);
//    var _uid = $(this).parents('.pm-user-ctrl').attr('id');
//
//    $.get('api/pmail.api.user.php',
//    {
//        p: 'follow', id: _uid
//    },
//
//    function(data)
//    {
//        if( data.msg )
//        {
//            pm('<div>').tip({content: data.msg}); return;
//        }
//
//        if( _btn.hasClass('pm-light-button') )
//        {
//            var _span = $('<span class="pm-gray-button"></span>');
//            if( $('.pm-icon', _btn).hasClass('ui-icon-plusthick') )
//                _span.html('<span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-arrowthick-1-w"></span></span>已信任&nbsp;|&nbsp;<a class="pm-follow-cancel">取消</a>');
//            else
//                _span.html('<span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-transferthick-e-w"></span></span>相互信任注&nbsp;|&nbsp;<a class="pm-follow-cancel">取消</a>');
//            _btn.replaceWith(_span);
//            _btn = $('a', _span);
//        }
//        else
//        {
//            _btn.text('取消信任');
//        }
//
//        _btn.unbind('click').click(pmUser.cancelFollow);
//        if( _btn.hasClass('pm-light-button') )
//            _btn.removeClass('pm-light-button').addClass('pm-gray-button');
//        _btn.removeClass('pm-follow').addClass('pm-follow-cancel');
//    }, 'json');
//};
//
//pmUser.cancelFollow = function()
//{
//    var _btn = $(this);
//    var _uid = $(this).parents('.pm-user-ctrl').attr('id');
//
//    $.get('api/pmail.api.user.php',
//    {
//        p: 'cancel-follow', id: _uid
//    },
//
//    function(data)
//    {
//        if( data.msg )
//        {
//            pm('<div>').tip({content: data.msg}); return;
//        }
//
//        if( _btn.hasClass('pm-gray-button') || _btn.parent().hasClass('pm-gray-button') )
//        {
//            _btn = _btn.parent().hasClass('pm-gray-button') ? _btn.parent() : _btn;
//            var _a = $('<a class="pm-follow pm-light-button"></a>');
//            if( $('.pm-icon', _btn).hasClass('ui-icon-transferthick-e-w') )
//                _a.html('<span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-arrowthick-1-e"></span></span>&nbsp;信任他');
//            else
//                _a.html('<span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-plusthick"></span></span>&nbsp;信任他');
//            _btn.replaceWith(_a);
//            _btn = _a;
//        }
//        else
//        {
//            _btn.text('信任他');
//        }
//
//        _btn.unbind('click').click(pmUser.follow);
//        if( _btn.hasClass('pm-gray-button') )
//            _btn.removeClass('pm-gray-button').addClass('pm-light-button');
//        _btn.removeClass('pm-follow-cancel').addClass('pm-follow');
//    }, 'json');
//};
//
//_pMail.user = pmUser;
//
//$(function()
//{
//    pm.user($('.pm-user-ctrl'));
//});
