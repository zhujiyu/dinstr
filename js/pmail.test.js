/*!
 * PMAIL项目 JavaScript v2.4.12
 *
 * @bref 邮件编辑器
 * @author 朱继玉
 * @copyright @2012 公众邮件网
 */
pmGood = function(selector, context)
{
    this.selector = selector;
    this.context = context;
    this.object = $(selector, context);

    this[0] = $(selector, context).append($('<div class="pm-mail-good" goodid="" source="" numiid="">\
<div class="info"><span class="price"></span>：<a class="source" target="_blank"></a><br><a class="title" href="" target="_blank"></a></div>\
<div class="photo"></div>\
</div>'));
    return this;
};

pmGood.prompttext = '\u76ee\u524d\u8fd8\u4e0d\u652f\u6301\u8be5\u7f51\u7ad9\u7684\u5546\u54c1\uff01';
pmGood.source = {tmall: "\u5929\u732b\u5546\u57ce", taobao: "\u6dd8\u5b9d\u7f51"};

pmGood.fn = pmGood.prototype =
{
    id: 0, source: 'tmall', url: '', num_iid: 0,

    tmall : function(_good)
    {
        var _target = $('.pm-mail-good', this[0]);

        _target.attr('numiid', _good.num_iid);
        _target.attr('source', _good.source);
        _target.attr('goodid', _good.ID);

        $('.title', _target).text(_good.title);
        $('.title', _target).attr('href', this.url);
        $('.photo', _target).append('<img src="' + _good.pic_url + '">');
        $('.price', _target).text(_good.price + '元');
        $('.source', _target).text(pmGood.source.tmall);
        $('.source', _target).attr('href', 'www.tmail.com');

        return this;
    },

    taobao: function(_good)
    {
        var _target = $('.pm-mail-good', this[0]);
        this.tmall(_good);
        $('.source', _target).text(pmGood.source.taobao);
        $('.source', _target).attr('href', 'www.taobao.com');
        return this;
    },

    parse : function(_url, _options)
    {
        var _idReg, _good = this,
            options = $.extend(
            {
                start: function(s){},
                end: function(s){},
                error: function(s){},
                success: function(s){}
            }, _options);

        this.num_iid = 0;
        this.url =  _url;

        if( _url === undefined || typeof _url !== 'string' )
        {
            options.error('\u5546\u54c1\u94fe\u63a5\u4e0d\u80fd\u7a7a\uff01');
            return this;
        }

        if( pm.regs.taobao.exec(_url) )
        {
            _idReg = /id=(\d+)/i.exec(_url);
            this.num_iid = _idReg[1];
            this.source = 'taobao';
        }
        else if( pm.regs.tmall.exec(_url) )
        {
            _idReg = /id=(\d+)/i.exec(_url);
            this.num_iid = _idReg[1];
            this.source = "tmall";
        }
        else if( pm.regs.jingdong.exec(_url) )
        {
            this.source = 'jingdong';
        }
        else
        {
            options.error(pmGood.prompttext);
            return this;
        }

        if( this.num_iid === 0 )
        {
            options.error(pmGood.prompttext);
            return this;
        }

        options.start();

        $.get('api/pmail.api.good.php',
        {
            p: 'parse',
            url: _good.url,
            id: _good.id,
            num_iid: _good.num_iid,
            src: _good.source
        },

        function(data)
        {
            options.end();
            if( data.err )
            {
                options.error(data.err);
            }
            else
            {
                _good.id = data.good.ID;
                _good.save(data.good);
                options.success(data.good);
                $('#good_id', _good[0]).val(data.good.ID);
            }
        }, 'json');

        return this;
    },

    save : function($good)
    {
        this.good = $good;
        if( this.source === 'tmall' )
            this.tmall($good);
        else if( this.source === 'taobao' )
            this.taobao($good);
    }
};

pm.fn.extend(
{
    tipParsing : function (str)
    {
        var _load = $('<div id="parsing" class="pm-load"><div class="ui-widget-overlay"></div></div>')
            .prependTo(this[0]).css({'position': 'absolute'}).fadeIn('normal');
        $(this[0]).css({'position': 'relative'});
        $('<span></span>').text(str).appendTo(_load);
        $('#parsing span', this[0]).css({'top': Math.floor((_load.height() - $('#parsing span', this[0]).height() - 10) / 2) + 'px'});
    },

    removeParsing: function()
    {
        $('#parsing', this[0]).remove();
    }
});

(function ($) {
	"use strict";

	if (undefined === $.wysiwyg) {
		throw "wysiwyg.image.js depends on $.wysiwyg";
	}

	if (!$.wysiwyg.controls) {
		$.wysiwyg.controls = {};
	}

	$.wysiwyg.controls.shopping =
    {
        init : function(Wysiwyg)
        {
            var _dialog = $('<div class="pm-good-dialog">\
<div class="pm-dialog-content">\
<div class="pm-good-box"></div>\
<div class="pm-parse-ctrl">网购商品：<input type="text" name="url" id="url" class="pm-border" value="http://"/>&nbsp;</div>\
<div class="pm-error"></div>\
</div>\
<div class="pm-dialog-foot pm-ctrl"><a id="cancel" class="pm-gray-button">取消</a><a id="ok" class="pm-light-button">确定</a></div>\
</div>');

            var _good = new pmGood('<div>'),
                _parse = $('.pm-parse-ctrl', _dialog);

//  http://detail.tmall.com/item.htm?spm=3.358051.300174.3.AFdWEY&id=16174657337
            var _pFunc = function()
            {
                if( $('#url', _parse).val() === "" )
                    return;
                _good.parse($('#url', _parse).val(), _pOption);
            };

            var _pOption =
            {
                start: function()
                {
                    pm(_parse).tipParsing('\u6b63\u5728\u89e3\u6790...');
                },

                end : function()
                {
                    pm(_parse).removeParsing();
                    $('#url', _parse).val('');
                },

                error : function(_text)
                {
                    $(".pm-error", _dialog).html(_text).show();
                    pm(_dialog).dialog("show");
                },

                success: function()
                {
                    _parse.hide();
                    $('.pm-good-box', _dialog).append(_good[0]).show();
                    pm(_dialog).dialog("show");
                }
            };

            $(".pm-error", _dialog).hide();
            pm(_dialog).dialog({modal: true, title: '插入网购商品'});

            $("#url", _dialog).focus(function()
            {
                $(".pm-error", _dialog).hide();
            }).
            bind('keyup', function(event)
            {
                if( event.keyCode === 13 )
                {
                    _pFunc();
                }
            }).
            change(_pFunc);

            $('#ok', _dialog).unbind('click').click(function()
            {
                if( _good.id === 0 )
                    return false;

                Wysiwyg.insertHtml('&nbsp;' + $('.pm-mail-good', _dialog).parent().html() + '&nbsp;');
                $(Wysiwyg.editorDoc).trigger("editorRefresh.wysiwyg");
                pm(_dialog).dialog("destroy");
            });
        }
    };

	/*
	 * Wysiwyg namespace: public properties and methods
	 */
	$.wysiwyg.controls.image =
    {
        init : function(Wysiwyg)
        {
            var _id = 0,
                _dialog = $('<div class="pm-photo-dialog">\
<div class="pm-dialog-content">\
<div class="pm-photo-box"></div>\
<div class="pm-photo-param">图片标题：<input type="text" name="title" id="title" class="pm-border"/></div>\
<div class="pm-upload-ctrl pm-local-photo">本地图片：<input type="file" id="pm-upload-img" name="pm-upload-img"/>&nbsp;<a class="pm-light-button" id="upload">上传</a><img class="uploading" src="css/images/loading.gif"/></div>\
<div class="pm-move-ctrl pm-web-photo">网络图片：<input type="text" name="url" id="url" class="pm-border" value="http://"/></div>\
</div>\
<div class="pm-dialog-foot pm-ctrl"><a id="cancel" class="pm-gray-button">取消</a><a id="ok" class="pm-light-button">确定</a></div>\
</div>'),
                _photo = $('<div class="pm-input-photo">\
<div class="title"></div>\
<div class="photo"><img src="css/images/sample.png"></div>\
</div>'),
                _loaded = function($photo)
            {
                _id = $photo.ID;
//                $('#photo_url', _photo).val($photo.big);
//                $('#photo_id', _photo).val($photo.ID);
//                $('.pm-photo', _photo).removeClass('pm-loaded-pic').attr('imgsrc', $photo.big);
//                pm('.pm-photo', _photo).loadImg();
//                $('.photo', _photo).append('<img src="' + $photo.big + '">');
                $('.photo img', _photo).attr('src', $photo.big);
                $('.pm-upload-ctrl, .pm-move-ctrl', _dialog).hide();
                $('.pm-photo-param', _dialog).show();
            };
//<input type="hidden" name="photo_ids[]" id="photo_id"></input>\
//<input type="hidden" name="photo_urls[]" id="photo_url"></input>\

            $('.pm-photo-box', _dialog).prepend(_photo).show();
            pm(_dialog).dialog({modal: true, title: '插入图片'});

            $('#ok', _dialog).unbind('click').click(function()
            {
                if( _id === 0 )
                    return false;

//                Wysiwyg.insertHtml("<img src='" + $('img', _photo).attr('src') + "'/>");
                $('.title', _photo).text($('input#title', _dialog).val());
                Wysiwyg.insertHtml('&nbsp;' + _photo.parent().html() + '&nbsp;');
                pm(_dialog).dialog("destroy");
            });
            $('#cancel', _dialog).click(function()
            {
                if( _id === 0 )
                    return false;
                $.get('api/pmail.api.photo.php', { p : 'delete', id : _id });
            });

            $('#upload', _dialog).click(function()
            {
                pm.upload(_dialog, {loaded : _loaded});
            });

            $('#url', _dialog).change(function()
            {
                pm.move(_dialog, {loaded : _loaded});
//                $('.photo img', _photo).attr('src', $(this).val());
//                $('.pm-photo', _photo).removeClass('pm-loaded-pic').attr('imgsrc', $(this).val());
//                pm('.pm-photo', _photo).loadImg();
            });
			$(Wysiwyg.editorDoc).trigger("editorRefresh.wysiwyg");
        }
    };

})(jQuery);

$(function()
{
    $('#wysiwyg').wysiwyg();
//    $('#wysiwyg').wysiwyg("insertHtml", '<div><good><img src="http://127.0.0.1/pmail/attach/xw500/26/32494612T1FwxJXfdaXXb1upjX.jpg"><name>测试网购商品</name><info>价格：<price></price> 来自：<shop></shop></info></good></div>');

//    $('#wysiwyg').wysiwyg("addControl", "upload", {
//        groupIndex: 50,
//	icon: 'css/images/picture_up.png',
//	tooltip: 'upload image',
//	tags: ['blockquote'],
//
//	exec: function ()
//        {
//            var range	= this.getInternalRange(),
//                common	= range.commonAncestorContainer,
//		blockquote = this.dom.getElement("blockquote");
//
//            // if a text node is selected, we want to make the wrap the whole element, not just some text
//            if (common.nodeType === 3)
//            {
//                common = common.parentNode;
//            }
//
//            if (blockquote && $(blockquote).hasClass("quote"))
//            {
//                $(common).unwrap();
//            }
//            else
//            {
//                if ("body" !== common.nodeName.toLowerCase()) {
//                    $(common).wrap("<blockquote class='quote' />");
//                }
//            }
//	},
//
//	callback: function (event, Wysiwyg)
//        {
//            alert("callback triggered!");
//	}
//    });

//    $('#img').click(function()
//    {
//        $('#upload').click();
//    });

//    $('#wysiwyg').wysiwyg("addControl", "quote", {
//				groupIndex: 2,
//				icon: 'css/images/quote02.gif',
//				tooltip: 'Quote',
//				tags: ['blockquote'],
//
//				exec: function () {
//					var range	= this.getInternalRange(),
//						common	= range.commonAncestorContainer,
//						blockquote = this.dom.getElement("blockquote");
//
//					// if a text node is selected, we want to make the wrap the whole element, not just some text
//					if (common.nodeType === 3) {
//						common = common.parentNode;
//					}
//
//					if (blockquote && $(blockquote).hasClass("quote")) {
//						$(common).unwrap();
//					}
//					else {
//						if ("body" !== common.nodeName.toLowerCase()) {
//							$(common).wrap("<blockquote class='quote' />");
//						}
//					}
//				},
//
//				callback: function (event, Wysiwyg) {
//					alert("callback triggered!");
//				}
//			});

//    $('#wysiwyg').wysiwyg(
//    {
//        controls:
//        {
//			bold          : { visible : true },
//			italic        : { visible : true },
//			underline     : { visible : true },
//			strikeThrough : { visible : false },
//
//			justifyLeft   : { visible : false },
//			justifyCenter : { visible : false },
//			justifyRight  : { visible : false },
//			justifyFull   : { visible : false },
//
//			indent  : { visible : true },
//			outdent : { visible : true },
//
//			subscript   : { visible : false },
//			superscript : { visible : false },
//
//			undo : { visible : false },
//			redo : { visible : false },
//
//			insertOrderedList    : { visible : true },
//			insertUnorderedList  : { visible : true },
//			insertHorizontalRule : { visible : true },
//
////			h4: {
////				visible: true,
////				className: 'h4',
////				command: ($.browser.msie || $.browser.safari) ? 'formatBlock' : 'heading',
////				arguments: ($.browser.msie || $.browser.safari) ? '<h4>' : 'h4',
////				tags: ['h4'],
////				tooltip: 'Header 4'
////			},
////			h5: {
////				visible: true,
////				className: 'h5',
////				command: ($.browser.msie || $.browser.safari) ? 'formatBlock' : 'heading',
////				arguments: ($.browser.msie || $.browser.safari) ? '<h5>' : 'h5',
////				tags: ['h5'],
////				tooltip: 'Header 5'
////			},
////			h6: {
////				visible: true,
////				className: 'h6',
////				command: ($.browser.msie || $.browser.safari) ? 'formatBlock' : 'heading',
////				arguments: ($.browser.msie || $.browser.safari) ? '<h6>' : 'h6',
////				tags: ['h6'],
////				tooltip: 'Header 6'
////			},
//
////			cut   : { visible : true },
////			copy  : { visible : true },
////			paste : { visible : true },
//
//			increaseFontSize : { visible : true },
//			decreaseFontSize : { visible : true },
//			html  : { visible: true },
//			exam_html: {
//				exec: function() {
//					this.insertHtml('<abbr title="exam">Jam</abbr>');
//					return true;
//				},
//				visible: true
//			}
//        },
//
//        events: {
//			click: function(event) {
//				if ($("#click-inform:checked").length > 0) {
//					event.preventDefault();
//					alert("You have clicked jWysiwyg content!");
//				}
//			}
//        }
//    });
//    pretreat: function(_url, _error)
//    {
//        var _idReg;
//        this.num_iid = 0;
//        this.url = _url;
//
//        if( _url === undefined || typeof _url !== 'string' )
//        {
//            if( typeof _error !== 'undefined' && $.isFunction(_error) )
//                _error('\u5546\u54c1\u94fe\u63a5\u4e0d\u80fd\u7a7a\uff01');
//            else
//                pm('<div>').tip({mess: '\u5546\u54c1\u94fe\u63a5\u4e0d\u80fd\u7a7a\uff01'});
//            return this;
//        }
//
//        if( pm.regs.taobao.exec(_url) )
//        {
//            _idReg = /id=(\d+)/i.exec(_url);
//            this.num_iid = _idReg[1];
//            this.source = 'taobao';
//        }
//        else if( pm.regs.tmall.exec(_url) )
//        {
//            _idReg = /id=(\d+)/i.exec(_url);
//            this.num_iid = _idReg[1];
//            this.source = "tmall";
//        }
//        else if( pm.regs.jingdong.exec(_url) )
//        {
//            this.source = 'jingdong';
//        }
//        else
//        {
//            if( typeof _error !== 'undefined' && $.isFunction(_error) )
//                _error(pmGood.prompttext);
//            else
//                pm('<div>').tip({content: pmGood.prompttext});
//        }
//
//        return this;
//    },

//            change(function()
//            {
//                var _good = new pmGood('<li class="ui-corner-all"></li>');
//                _good.parse($('#url', _parse).val(),
//                {
//                    start: function()
//                    {
//                        pm(_parse).tipParsing('\u6b63\u5728\u89e3\u6790...');
//                    },
//
//                    error : function(_text)
//                    {
//                        $(".pm-error", _dialog).html(_text).show();
//                    },
//
//                    end : function()
//                    {
//                        pm(_parse).removeParsing();
//                        $('#url', _parse).val('');
//                    },
//
//                    success: function($good)
//                    {
//                        _parse.hide();
//                        $('.pm-good-box', _dialog).append(_good[0]).show();
//                        pm('.pm-good-photo', _dialog).loadImg();
//                        pm(_dialog).dialog("show");
//                    }
//                });
//            });

//                    pm('[imgsrc]', _dialog).loadImg();
//                Wysiwyg.insertHtml('<div id="' + _good.id + '">');
//                alert('.wysiwyg good#' + _good.id);
//                $('.wysiwyg #' + _good.id).text('怎么回事啊');
//                $('.wysiwyg good#' + _good.id).append($('.pm-good-box', _dialog));
//                $('.wysiwyg good#' + _good.id).css({'background-color': 'red'});
//                alert($('.pm-good-box', _dialog).html());
//                Wysiwyg.insertHtml($('.pm-mail-good', _good[0]).html());

//                    pm('.photo', _dialog).loadImg();
//                    $('.pm-good-box', _dialog).append($('.pm-mail-good', _good[0])).show();
//                    $('.pm-good-box', _dialog).append($('good', _good[0])).show();

//            if( typeof options.error !== 'undefined' && $.isFunction(options.error) )
//                options.error('\u5546\u54c1\u94fe\u63a5\u4e0d\u80fd\u7a7a\uff01');
//            else
//                pm('<div>').tip({mess: '\u5546\u54c1\u94fe\u63a5\u4e0d\u80fd\u7a7a\uff01'});
//
//            var _good = new pmGood('<li class="ui-corner-all"></li>'),
//            var _good = new pmGood('.pm-good-box', _dialog),

//    this[0] = $(selector, context).append($('<div class="good"><photo><img></photo><br><strong><a id="name"></a></strong><br><info>价格：<price></price>&nbsp;店铺：<a id="shop"></a></info></div>'));
//    this[0] = $(selector, context).append($('<div class="good"><photo><img></photo><br><strong><a id="name"></a></strong><br><info>价格：<price></price>&nbsp;店铺：<a id="shop"></a></info></div>'));
//    this[0] = $(selector, context).append($('<good><photo><img></photo><br><strong><a id="name"></a></strong><br><info>价格：<price></price>&nbsp;店铺：<a id="shop"></a></info></good>'));
//
//        var _target = this[0];
//        $('good', _target).attr('numiid', _good.num_iid);
//        $('good', _target).attr('source', _good.source);
//        $('good', _target).attr('id', _good.ID);
//        $('img', _target).attr('src', _good.pic_url).css({'max-width': '500px', 'max-height':'500px'});
//        $('a#name', _target).attr('href', this.url);
//        $('a#name', _target).text(_good.title);
//
//        $('.photo', _target).attr('imgsrc', _good.pic_url);
//        $('price', _target).empty();
//        $('price', _target).text(_good.price);
//        $('a#shop', _target).text(_good.nick);
//        $('a#shop', _target).attr('href', 'www.tmail.com');
//        $('a#source', _target).text(pmGood.source.tmall);
//        $('a#source', _target).attr('href', 'www.tmail.com');

//        $('.shop a', _target).text(_good.nick);
//        $('.shop a', _target).attr('href', 'www.tmail.com');
});