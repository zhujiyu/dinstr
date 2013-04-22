/*!
 * PMAIL项目 JavaScript v2.4.12
 *
 * 该文件依赖 pmail.core.js pmail.channel.js
 *
 * @bref 公共初始化操作
 * @author 朱继玉
 * @copyright @2012 公众邮件网
 */
$(function()
{
    alert(pm.extend);
//    $('.pm-red-star').html('<span class="pm-icon ui-icon-star"></span>');
//
//    $('.pm-page-navi').each(function()
//    {
//        var _navi = $(this), _curr = $(this).attr('view');
//        if( _curr === undefined || _curr === '' )
//            _curr = 'default';
//        
//        $('.item', _navi).addClass('ui-corner-top');
//        $('.' + _curr, _navi).addClass('current');
//        $('.' + _curr + ' a', _navi).removeAttr('href');
//    });
//    
//    $('.pm-content-navi').each(function()
//    {
//        var _navi = $(this);
//        var _item = $('.pm-content-navi').attr('view');
//        if( _item === undefined || _item === '' )
//            _item = 'default';
//        
//        $('.' + _item, _navi).addClass('current').css({'cursor': 'text'});
//        $('.' + _item + ' a', _navi).removeAttr('href');
//    });
//
//    pm('.pm-search-form #keyword').promptText();
//    if( $.ui && $.ui.autocomplete )
//    {
//        $('.pm-search-form #keyword')
//        .autocomplete({source: "api/pmail.api.search.php", minLength: 1})
//        .data( "autocomplete" )._renderItem = function( ul, item )
//        {
//            var _str = item.type === 'channel' ? pMail.Channel.RenderStr(item) : "<div class=\"pm-dropdown-item\"><div class=\"pm-inline-block\"><div class=\"name\">" + item.label + "</div>" + item.desc + "</div></div>";
//            return $( "<li></li>" ).data( "item.autocomplete", item ).append( "<a>" + _str + "</a>" ).appendTo( ul );
////            var _str;
////            if( item.type === 'channel' )
////            {
////                _str = pMail.Channel.RenderStr(item);
////            }
////            else
////            {
////                _str = "<div class=\"pm-dropdown-item\"><div class=\"pm-inline-block\"><div class=\"name\">" + item.label + "</div>" + item.desc + "</div></div>";
////            }
////            return $( "<li></li>" ).data( "item.autocomplete", item ).append( "<a>" + _str + "</a>" ).appendTo( ul );
//        };
//    }
//
//    var _rtop = $('<span class="pm-to-top pm-a ui-corner-all"><span class="pm-arrow pm-arrow-up">'
//        + '<em class="head">&diams;</em><em class="tear">▐</em></span>返回顶部</span>');
//    _rtop.appendTo($(document.body)).click(function()
//    {
//        window.scroll(0, 0);
//        return false;
//    });
//
//    if( $('.pm-page-left').length === 0 )
//    {
//        $('.pm-to-top').css({'margin-left': '470px'});
//    }
//    pm('.pm-err').close({icon: 'ui-icon-close'});
//
//    $(window).scroll(function()
//    {
//        $('.pm-unload-pic').each(function()
//        {
//            pmLoadImg($(this), pmLoadImg.ZoomForIE6);
//        });
//        
//        if( $(window).scrollTop() > 500 )
//            _rtop.show();
//        else
//            _rtop.hide();
//    });
//    
//    $('.pm-load-display[imgsrc]').each(function()
//    {
//        pmLoadImg($(this), pmLoadImg.ZoomForIE6);
//    });
//    
//    pm('.pm-channel-manage').close();
});
