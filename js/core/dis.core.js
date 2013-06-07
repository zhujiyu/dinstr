// Returns the JSON representation of a value
//
// version: 1109.2015
// discuss at: http://phpjs.org/functions/json_encode
// +      original by: Public Domain (http://www.json.org/json2.js)
// + reimplemented by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
// +      improved by: Michael White
// +      input by: felix
// +      bugfixed by: Brett Zamir (http://brett-zamir.me)
// *        example 1: json_encode(['e', {pluribus: 'unum'}]);
// *        returns 1: '[\n    "e",\n    {\n    "pluribus": "unum"\n}\n]'
/*
        http://www.JSON.org/json2.js
        2008-11-19
        Public Domain.
        NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.
        See http://www.JSON.org/js.html
 */
//function json_encode (mixed_val)
jQuery.json_encode = function (mixed_val)
{
    var retVal, json = window.JSON || this.window.JSON;
    try
    {
        if( typeof json === 'object' && typeof json.stringify === 'function' )
        {
            retVal = json.stringify(mixed_val);
            // Errors will not be caught here if our own equivalent to resource
            //  (an instance of PHPJS_Resource) is used
            if( retVal === undefined )
            {
                throw new SyntaxError('json_encode');
            }
            return retVal;
        }

        var value = mixed_val;
        var quote = function (string)
        {
            var escapable = /[\\\"\u0000-\u001f\u007f-\u009f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g;
            var meta =
            {
                // table of character substitutions
                '\b': '\\b',
                '\t': '\\t',
                '\n': '\\n',
                '\f': '\\f',
                '\r': '\\r',
                '"': '\\"',
                '\\': '\\\\'
            };

            escapable.lastIndex = 0;
            return escapable.test(string) ? '"' + string.replace(escapable, function (a)
            {
                var c = meta[a];
                return typeof c === 'string' ? c : '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
            }) + '"' : '"' + string + '"';
        };

        var str = function (key, holder)
        {
            var gap = '';
            var indent = '    ';
            var i = 0; // The loop counter.
            var k = ''; // The member key.
            var v = ''; // The member value.
            var length = 0;
            var mind = gap;
            var partial = [];
            var value = holder[key];

            // If the value has a toJSON method, call it to obtain a replacement value.
            if( value && typeof value === 'object' && typeof value.toJSON === 'function' )
            {
                value = value.toJSON(key);
            }

            // What happens next depends on the value's type.
            switch (typeof value)
            {
            case 'string':
                return quote(value);

            case 'number':
                // JSON numbers must be finite. Encode non-finite numbers as null.
                return isFinite(value) ? String(value) : 'null';

            case 'boolean':
            case 'null':
                // If the value is a boolean or null, convert it to a string. Note:
                // typeof null does not produce 'null'. The case is included here in
                // the remote chance that this gets fixed someday.
                return String(value);

            case 'object':
                // If the type is 'object', we might be dealing with an object or an array or
                // null.
                // Due to a specification blunder in ECMAScript, typeof null is 'object',
                // so watch out for that case.
                if( !value )
                {
                    return 'null';
                }

                if( (this.PHPJS_Resource && value instanceof this.PHPJS_Resource) || (window.PHPJS_Resource && value instanceof window.PHPJS_Resource) )
                {
                    throw new SyntaxError('json_encode');
                }

                // Make an array to hold the partial results of stringifying this object value.
                gap += indent;
                partial = [];

                // Is the value an array?
                if (Object.prototype.toString.apply(value) === '[object Array]')
                {
                    // The value is an array. Stringify every element. Use null as a placeholder
                    // for non-JSON values.
                    length = value.length;
                    for (i = 0; i < length; i += 1)
                    {
                        partial[i] = str(i, value) || 'null';
                    }

                    // Join all of the elements together, separated with commas, and wrap them in
                    // brackets.
                    v = partial.length === 0 ? '[]' : gap ? '[\n' + gap + partial.join(',\n' + gap) + '\n' + mind + ']' : '[' + partial.join(',') + ']';
                    gap = mind;
                    return v;
                }

                // Iterate through all of the keys in the object.
                for (k in value)
                {
                    if( Object.hasOwnProperty.call(value, k) )
                    {
                        v = str(k, value);
                        if (v)
                        {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }

                // Join all of the member texts together, separated with commas,
                // and wrap them in braces.
                v = partial.length === 0 ? '{}' : gap ? '{\n' + gap + partial.join(',\n' + gap) + '\n' + mind + '}' : '{' + partial.join(',') + '}';
                gap = mind;
                return v;
            case 'undefined':
                // Fall-through
            case 'function':
                // Fall-through
            default:
                throw new SyntaxError('json_encode');
            }
        };

        // Make a fake root object containing our value under the key of ''.
        // Return the result of stringifying the value.
        return str('', {'': value});
    }
    catch (err)
    {
        // Todo: ensure error handling above throws a SyntaxError in all cases where it could
        // (i.e., when the JSON global is not available and there is an error)
        if( !(err instanceof SyntaxError) )
        {
            throw new Error('Unexpected error type in json_encode()');
        }

        this.php_js = this.php_js || {};
        this.php_js.last_error_json = 4; // usable by json_last_error()
        return null;
    }
};

/**
 * jQuery Cookie plugin
 *
 * Copyright (c) 2010 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */

/**
 * Create a cookie with the given key and value and other optional parameters.
 *
 * @example $.cookie('the_cookie', 'the_value');
 * @desc Set the value of a cookie.
 * @example $.cookie('the_cookie', 'the_value', { expires: 7, path: '/', domain: 'jquery.com', secure: true });
 * @desc Create a cookie with all available options.
 * @example $.cookie('the_cookie', 'the_value');
 * @desc Create a session cookie.
 * @example $.cookie('the_cookie', null);
 * @desc Delete a cookie by passing null as value. Keep in mind that you have to use the same path and domain
 *       used when the cookie was set.
 *
 * @param String key The key of the cookie.
 * @param String value The value of the cookie.
 * @param Object options An object literal containing key/value pairs to provide optional cookie attributes.
 * @option Number|Date expires Either an integer specifying the expiration date from now on in days or a Date object.
 *                             If a negative value is specified (e.g. a date in the past), the cookie will be deleted.
 *                             If set to null or omitted, the cookie will be a session cookie and will not be retained
 *                             when the the browser exits.
 * @option String path The value of the path atribute of the cookie (default: path of page that created the cookie).
 * @option String domain The value of the domain attribute of the cookie (default: domain of page that created the cookie).
 * @option Boolean secure If true, the secure attribute of the cookie will be set and the cookie transmission will
 *                        require a secure protocol (like HTTPS).
 * @type undefined
 *
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */

/**
 * Get the value of a cookie with the given key.
 *
 * @example $.cookie('the_cookie');
 * @desc Get the value of a cookie.
 *
 * @param String key The key of the cookie.
 * @return The value of the cookie.
 * @type String
 *
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */
jQuery.cookie = function (key, value, options)
{
    // key and value given, set cookie...
    if (arguments.length > 1 && (value === null || typeof value !== "object"))
    {
        options = jQuery.extend({}, options);

        if (value === null)
        {
            options.expires = -1;
        }

        if (typeof options.expires === 'number')
        {
            var days = options.expires, t = options.expires = new Date();
            t.setDate(t.getDate() + days);
        }

        return (document.cookie = [
            encodeURIComponent(key), '=',
            options.raw ? String(value) : encodeURIComponent(String(value)),
            options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
            options.path ? '; path=' + options.path : '',
            options.domain ? '; domain=' + options.domain : '',
            options.secure ? '; secure' : ''
        ].join(''));
    }

    // key and possibly options given, get cookie...
    options = value || {};
    var result, decode = options.raw ? function (s) { return s; } : decodeURIComponent;
    return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
};

/*!
 * DIS项目 JavaScript v1.1.12
 *
 * @brief 本文件是DIS项目JS代码的根文件，提供了JS代码的命名空间和基础类，及几个常用函数
 * @author 朱继玉<zhuhz82@126.com>
 * @copyright @2013 海报栏
 */
// 定义DIS项目的js架构的根对象
disBase = function()
{
    return this;
};

disBase.fn = disBase.prototype = {};
disBase.extend = disBase.fn.extend = $.extend;

_dis = function(selector, context)
{
    return new _dis.fn.init(selector, context);
};
_dis.fn = _dis.prototype = new disBase();

// 扩展根对象dis的功能，实现一些最基本的功能
_dis.fn.extend(
{
    name: "DIS",
    author: "zhujiyu",
    copyright: " Copyright @2013 ",
    version: "1.1.6",

    constructor: _dis,

    init: function(selector, context)
    {
        this.selector = selector;
        this.context = context;
        this.object = $(selector, context);
        this.length = this.object.length;
        this[0] = $(selector, context);
        return this;
    },

    // 给对象加一个关闭按钮
    close: function(_option)
    {
        var _obj = $(this[0]).css({'position': 'relative'}),

            _onClose = function()
            {
                _obj.remove();
            },
            _settings = $.extend(
            {
                onClose: _onClose,
                icon: 'ui-icon-closethick'
            }, _option);

        _obj.prepend('<span id="close" class="dis-close ui-corner-all"><span class="dis-icon"></span></span>');
        $('.dis-icon', _obj).addClass(_settings.icon).click(_settings.onClose);

        return this;
    },

    // 提示正在加载
    loading : function()
    {
        $('<div id="load" class="dis-loading"></div>').appendTo(this[0])
            .append('<div class="dis-content-border"></div>')
            .append('<div class="dis-loading-pic"><img src="css/images/loading.gif"><div>\u6b63\u5728\u52a0\u8f7d...</div></div>');
    },

    loaded: function()
    {
        $('#load', this[0]).remove();
    },

    // 向对象加闪烁提示
    prompt : function()
    {
        var _self = $(this[0]).addClass('dis-prompt');
        var _prnt = $(this[0]).parent().addClass('dis-prompt-wrap');

        setTimeout(function()
        {
            _self.removeClass('dis-prompt');
            _prnt.removeClass('dis-prompt-wrap');
        }, 1000);

        return this;
    },

    promptText : function(_text)
    {
        var _prompt = function()
        {
            var _vlu = _text ? _text : $(this).val();

            $(this).val(_vlu).focus(function()
            {
                if( $(this).val() === _vlu )
                    $(this).val('');
            })
            .blur(function()
            {
                if( $(this).val() === '' )
                    $(this).val(_vlu);
            });
        };

        this[0].filter('textarea, input:text').each(_prompt);
        return this;
    }
});

_dis.extend = _dis.fn.extend = $.extend;
_dis.fn.init.prototype = _dis.fn;
window.dis = _dis;
window.pMail = _pMail = _dis;

disDialog = function(_dialog, _options)
{
    var _dlgId  = $(_dialog).attr('dlgId'), _oldDlg = $('.dis-dialog[dlgId=' + _dlgId + ']');

    if( typeof _dlgId !== 'undefined' && _oldDlg.length > 0 )
    {
        this.dialog = _oldDlg;
        this.options = $.parseJSON($(_dialog).attr('options'));
        this.overlay = $('.ui-widget-overlay[oveId=' + _dlgId + ']');
    }
    else
    {
        this.dialog = $(_dialog);
        this.create(_options);
    }

    return this;
};

disDialog.count = 100;
disDialog.zIndex = 99;

disDialog.fn = disDialog.prototype =
{
    create : function(_options)
    {
        this.options = $.extend({
            autoOpen : true,
            modal : false,
            title: '操作提示',
            content: '',
            foot : '',
            zIndex: disDialog.zIndex,
            left: 'auto',
            top: 'auto',
            draggable: true,
            resizable: false
        }, _options);

        this.dlgId = disDialog.count;
        disDialog.count ++;
        this.dialog.attr('options', $.json_encode(this.options));

        if( this.options.modal )
        {
            this.overlay = $('<div class="ui-widget-overlay"></div>').attr('oveId', this.dlgId)
                .appendTo($(document.body)).css({'z-index': this.options.zIndex});
            disDialog.zIndex ++;
        }

        this.dialog.addClass('dis-dialog ui-corner-all').attr('dlgId', this.dlgId).appendTo($(document.body)).css({"z-index": this.options.zIndex + 1});
        disDialog.zIndex ++;

        if( $('.dis-dialog-head', this.dialog).length === 0 )
            $('<div class="dis-dialog-head ui-corner-top"><span class="dis-dialog-title"></span></div>').prependTo(this.dialog);
        else
            $('.dis-dialog-head', this.dialog).addClass('ui-corner-top');
        $('.dis-dialog-title', this.dialog).text(this.options.title);
        $(this.dialog).attr('title', this.options.title);

        if( $('.dis-dialog-content', this.dialog).length === 0 )
            $('.dis-dialog-head', this.dialog).after($('<div class="dis-dialog-content"></div>'));
        if( this.options.content )
            $('.dis-dialog-content', this.dialog).html(this.options.content);
        if( this.options.mess )
            $('.dis-dialog-content', this.dialog).text(this.options.mess);

        if( $('.dis-dialog-foot', this.dialog).length === 0 )
            $('<div class="dis-dialog-foot dis-ctrl"><a id="ok" class="dis-light-button">\u786e\u5b9a</a></div>').appendTo(this.dialog);
        if( this.options.foot )
            $('.dis-dialog-foot', this.dialog).html(this.options.foot);

        var _dialog = this,
            _destroy  = function(){ _dialog.destroy(); };
        dis('.dis-dialog-head', this.dialog).close({onClose: _destroy});
        $('.dis-dialog-foot .dis-gray-button, .dis-dialog-foot .dis-light-button', this.dialog).click(_destroy);

        if( $.ui !== undefined )
        {
            if( this.options.draggable && $.ui.draggable !== undefined )
                this.dialog.draggable({handle: '.dis-dialog-head'});
            if( this.options.resizable && $.ui.draggable !== undefined )
                this._resize();
        }

        if( this.options.autoOpen )
            this.show();
        else
            this.hide();
    },

    _resize : function()
    {
        var _width  = this.dialog.width();
        var _height = this.dialog.height() - $('.dis-dialog-head', this).height() - $('.dis-dialog-foot', this).height();

        this.dialog.resizable(
        {
            resize: function()
            {
                $('.dis-dialog-content', this).height($(this).height() - _height);
            }
        })
        .width(_width);
    },

    show : function()
    {
        var _left, _top;
        if( this.overlay !== undefined )
            this.overlay.show();
        this.dialog.show();

        if( this.options.padding )
            $('.dis-dialog-content', this.dialog).css({'padding': this.options.padding});

        if( this.options.left !== 'auto' )
            _left = parseInt(this.options.left);
        else
            _left = parseInt(($(window).width() - this.dialog.width()) / 2);

        if( this.options.top !== 'auto' )
            _top = parseInt(this.options.top);
        else
            _top  = parseInt($(window).scrollTop() + ($(window).height() - this.dialog.height()) / 2);

        _left = Math.max(_left, 0);
        _top = Math.max(_top, 0);
        this.dialog.css({'left': _left + 'px', 'top': _top + 'px'});
    },

    hide : function()
    {
        this.dialog.hide();
        if( this.overlay !== undefined )
            this.overlay.hide();
    },

    destroy : function()
    {
        this.dialog.remove();
        if( this.overlay !== undefined )
            this.overlay.remove();
    }
};

_dis.fn.extend(
{
    dialog: function(_option)
    {
        if( this.disDialog ===  undefined )
        {
            this.disDialog =  new disDialog($(this[0]), _option);
        }

        if( typeof _option === 'string' )
        {
            if( _option === 'show' )
            {
                this.disDialog.show();
            }
            else if( _option === 'close' )
                this.disDialog.close();
            else if( _option === 'destroy' )
                this.disDialog.destroy();
        }

        return this;
    },

    tip: function(_option)
    {
        var _tip = this[0].addClass('dis-tip-dialog');
        var $option = $.extend(
        {
            modal: true,
            width: 300,
            height: 40
        }, _option);

        $('<div class="dis-dialog-content"></div>').appendTo(_tip).css(
        {
            'padding': '20px',
            'min-width': $option.width + 'px',
            'min-height': $option.height + 'px'
        });
        this.disDialog = new disDialog(_tip, $option);

        return this;
    }
});

disLoadImg = function (_pic)
{
    if( $(_pic).hasClass('dis-loaded-pic') || $(_pic).hasClass('dis-loading-pic') )
        return;

    // 第一步，加未加载标志
    if( $(_pic).hasClass('dis-unload-pic') === false )
        $(_pic).addClass('dis-unload-pic');

    // 第二步，判断是否可见
    if( _pic.offset().top + _pic.height() < $(window).scrollTop()
        || _pic.offset().top > $(window).scrollTop() + document.documentElement.clientHeight )
        return;

    // 第三步，打上正在加载标志
    if( _pic.attr('imgsrc') === undefined || _pic.attr('imgsrc') === '' )
        _pic.attr('imgsrc', 'css/logo/avatar.png');
    $(_pic).addClass('dis-loading-pic').removeClass('dis-unload-pic');

    // 第四步，加载
    // 3.1 先插入一个<img>结点
    if( $('img', _pic).length < 1 )
    {
        if( $('a', _pic).length === 1 )
            $('a', _pic).append('<img>');
        else
            $(_pic).append('<img>');
    }

    // 3.2 加载图片
    $('img', _pic).css('display', 'none').attr('src', _pic.attr('imgsrc')).fadeIn('fast', function()
    {
        if( _pic.hasClass('dis-tile-img') )
            disLoadImg.Adjust(_pic);
        $(_pic).addClass('dis-loaded-pic').removeClass('dis-loading-pic');
    });
};

disLoadImg.Adjust = function(pic)
{
    $(pic).filter('div').each(function()
    {
        var _img = $('img', this), _pic = $(this),
            iw = _img.width(), ih = _img.height(),
            dw = _pic.width(),
            mwh, px, py;

        if( _img.attr('src') === undefined || _img.attr('src') === '' )
            return;

        if( iw > ih )
        {
            mwh = dw * iw / ih;
            px = Math.round((dw - mwh) * 0.5);
            py = 0;
        }
        else
        {
            mwh = dw * ih / iw;
            px = 0;
            py = Math.round((dw - mwh) * 0.5);
        }

        _img.css({'max-width': Math.round(mwh) + "px", 'max-height': Math.round(mwh) + "px"});
        _img.css({'left': Math.round(px) + "px", 'top': Math.round(py) + "px"});
    });
};

_dis.fn.extend(
{
    loadImg: function()
    {
        $(this[0]).each(function()
        {
            disLoadImg($(this));
        });
    },

    chanType : function(type)
    {
        if( type === 'social' )
            return "\u793e\u4ea4";
        else if( type === 'business' )
            return "\u5546\u52a1";
        else if( type === 'info' )
            return "\u8d44\u8baf";
        else if( type === 'news' )
            return "\u65b0\u95fb";
        return "\u793e\u4ea4";
    },

    renderStr : function( _chan )
    {
        var
            _logo = _chan.logo ? _chan.logo.small : 'css/logo/chanbgs.png',
            _desc = _chan.desc ? _chan.desc : '';

        _desc = _desc.length > 25 ? _desc.substr(0, 25) + '...' : _desc;
        return  '<div class="dis-dropchan-item"><div class="dis-dropdown-icon dis-avatar-small dis-avatar-img dis-inline-block" imgsrc="'
            + _logo + '"></div><div class="dis-dropdown-cont dis-inline-block"><div><div class="name dis-inline-block">'
            + _chan.name + '</div><div class="param dis-inline-block">'
            + '\u6210\u5458 ' + _chan.member_num + ' \u8ba2\u9605 ' + _chan.subscriber_num + ' \u4fe1\u606f ' + _chan.mail_num + '</div></div><div class="desc">'
            + _desc + '</div></div><div class="type dis-inline-block">' + dis.chanType(_chan.type) + '</div></div><div class="dis-border-line"></div>';
    }
});

_dis.regs =
{
    taobao : /^(http:\/\/)?\w+.taobao.com\/item/i,
    tmall: /^(http:\/\/)?\w+.tmall.com\//i,
    jingdong : /^(http:\/\/)?\w+.360buy.com\/[\w- .\/\?%&=:,]*$/i,
    url : /^(http:\/\/)?([\w-]+.)+[\w-]+(\/[\w- .\/\?%&=:,]*)?$/i,
    select : /^[#\.]?[\w-]+([, ]+[#\.]?[\w-]+)*$/i,
    kwRegs : /#([\w\u4e00-\u9fa5]+)#/g
};

/**jQuery autoResize (textarea auto-resizer)
 * @copyright James Padolsey http://james.padolsey.com
 * @version 1.04
 */
(function($)
{
    $.fn.autoResize = function(options)
    {
        // Just some abstracted details,
        // to make plugin users happy:
        var settings = $.extend({
            onResize : function(){},
            onResized : function(){},
            animate : true,
            animateDuration : 100,
            animateCallback : function(){},
            extraSpace : 10,
            limit: 600,
            auto: true
        }, options);

        // Only textarea's auto-resize:
        this.filter('textarea').each(function()
        {
                // Get rid of scrollbars and disable WebKit resizing:
            var textarea = $(this).css({resize:'none', 'overflow-y':'hidden'}),

                // Cache original height, for use later:
                origHeight = textarea.height(),

                // Need clone of textarea, hidden off screen:
                clone = (function()
                {
                    // Properties which may effect space taken up by chracters:
                    var props = ['height','width','lineHeight','textDecoration','letterSpacing'],
                        propOb = {};

                    // Create object of styles to apply:
                    $.each(props, function(i, prop){
                        propOb[prop] = textarea.css(prop);
                    });

                    // Clone the actual textarea removing unique properties
                    // and insert before original textarea:
                    return textarea.clone().removeAttr('id').removeAttr('name')
                        .css({position: 'absolute', top: 0, left: -9999}).css(propOb)
                        .attr('tabIndex','-1').insertBefore(textarea);
                })(),
                lastScrollTop = null,

                updateSize = function()
                {
                    // Prepare the clone:
                    clone.height(0).val($(this).val()).scrollTop(10000);

                    // Find the height of text:
                    var scrollTop = Math.max(clone.scrollTop(), origHeight) + settings.extraSpace,
                        toChange = $(this).add(clone);

                    // Don't do anything if scrollTip hasen't changed:
                    if( lastScrollTop === scrollTop ) {return;}
                    lastScrollTop = scrollTop;

                    // Check for limit:
                    if( scrollTop >= settings.limit )
                    {
                        $(this).css('overflow-y','');
                        return;
                    }
                    // Fire off callback:
                    settings.onResize.call(this);

                    // Either animate or directly apply height:
                    settings.animate && textarea.css('display') === 'block' ?
                        toChange.stop().animate({height:scrollTop}, settings.animateDuration, settings.animateCallback)
                        : toChange.height(scrollTop);
                    settings.onResized.call(this);
                };

            // Bind namespaced handlers to appropriate events:
            textarea
                .unbind('.dynSiz')
                .bind('keyup.dynSiz', updateSize)
                .bind('keydown.dynSiz', updateSize)
                .bind('change.dynSiz', updateSize);

            if( settings.auto )
                textarea.trigger('keydown');
        });

        // Chain:
        return this;
    };
})(jQuery);

$(function()
{
    $('.dis-dropdown-menu').each(function()
    {
        $(this).prepend('<div class="dis-triangle dis-triangle-top"></div>');
    }).addClass('dis-corner-all');

    var _srch = $('.dis-search-form #keyword');
    if( $.ui && $.ui.autocomplete && _srch.length > 0 )
    {
        dis(_srch).promptText();
        try
        {
            _srch.autocomplete({source: "api/search.api.php", minLength: 1})
            .data( "autocomplete" )._renderItem = function( ul, item )
            {
                var _str = item.type === 'channel' ? dis.renderStr(item) : "<div class=\"dis-dropdown-item\"><div class=\"dis-inline-block\"><div class=\"name\">" + item.label + "</div>" + item.desc + "</div></div>";
                return $( "<li></li>" ).data( "item.autocomplete", item ).append( "<a>" + _str + "</a>" ).appendTo( ul );
            };
        }
        catch( err )
        {
            alert(err);
        }
    }

    var _rtop = $('<span class="dis-to-top dis-a ui-corner-all"><span class="dis-arrow dis-arrow-up">'
        + '<em class="head">&diams;</em><em class="tear">▐</em></span>返回顶部</span>');
    _rtop.appendTo($(document.body)).click(function()
    {
        window.scroll(0, 0);
        return false;
    });
//    if( $('.dis-page-left').length === 0 )
//    {
//        $('.dis-to-top').css({'margin-left': '470px'});
//    }

    $('.dis-load-display[imgsrc]').each(function()
    {
        disLoadImg($(this));
    });

    $(window).scroll(function()
    {
        $('.dis-unload-pic').each(function()
        {
            disLoadImg($(this));
        });

        if( $(window).scrollTop() > 500 )
            _rtop.show();
        else
            _rtop.hide();
    });

    dis('.dis-err').close({icon: 'ui-icon-close'});
    $($('.dis-chan-navi')[0]).addClass('dis-chan-current');
    $('.dis-red-star').html('<span class="dis-icon ui-icon-star"></span>');

//    dis('<div>').dialog();
//    dis('<div>').tip({mess: '提示一下试试'});
});

//$(function()
//{
//    $('.dis-page-navi').each(function()
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
//    $('.dis-content-navi').each(function()
//    {
//        var _item = $(this).attr('view');
//        if( _item === undefined || _item === '' )
//            _item = 'default';
//
//        $('.' + _item + ' a', this).removeAttr('href');
//        $('.' + _item, this).addClass('current').css({'cursor': 'text'}).parent().prependTo(this);
//        $('.dis-content-border', this).remove();
//        $('<div class="dis-content-border"></div>').appendTo($(this).children('div'));
//        $('.dis-content-border:last', this).remove();
//    });
//});
