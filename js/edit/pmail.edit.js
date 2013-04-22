/*!
 * PMAIL项目 JavaScript v2.4.12
 *
 * @bref 邮件编辑器
 * @author 朱继玉
 * @copyright @2012 公众邮件网
 */
pmRuler = function ()
{
    this.ruler = $('<div class="pm-mail-weight">\
<input type="text" name="weight" class="pm-no-border" value="设置优先级"></input>\
<div class="pm-ruler pm-inline-block" title="">\
<div class="pm-tag-list"></div>\
<div class="pm-mark-list"><div class="pm-first-mark pm-inline-block"></div></div>\
<div class="pm-ruler-line"></div>\
</div></div>');

    for( var i = 0, _txt = 1; i < 5; i ++ )
    {
        $('.pm-mark-list', this.ruler).append('<div class="pm-mark pm-inline-block"></div>');
        $('<div class="pm-tag pm-inline-block"></div>').text(_txt).appendTo($('.pm-tag-list', this.ruler));
        _txt *= 10;
    }
    $('.pm-tag:first', this.ruler).text(1);

    var _ruler = this;
    $('input', this.ruler).blur(function()
    {
        var _val = _ruler.unPreg($(this).val());
        $('.pm-ruler-line', _ruler.ruler).width(_val);
        $(this).val(_ruler.preg(_val));
    });

    $('.pm-ruler', this.ruler).mousedown(function()
    {
        $('input', $(this).parent()).val($(this).attr('title'));
        $('.pm-ruler-line', _ruler.ruler).width(_ruler.unPreg($(this).attr('title')));
    }).

    mousemove(function(e)
    {
        var _left = e.clientX - $(this).offset().left;
        $(this).attr('title', _ruler.preg(_left));
    });

    return this;
};

pmRuler.fn = pmRuler.prototype =
{
    // 把点击的长度，转换成优先级的值
    preg : function (_left)
    {
        if( _left < 10 )
            return 0;
        else if( _left <= 19 )
            return 1;
        _left -= 5;

        var _d = parseInt(_left / 90);
        var _s = parseInt(_left % 90);
        var _b = parseInt(_s / 10) +1;
        for( var a = 0; a < _d; a ++ )
            _b *= 10;
        return _b;
    },

    unPreg : function (_v)
    {
        _v = parseInt(_v);
        if( isNaN(_v) )
            _v = 0;

        for( var a = 0, b = _v; b > 10; a ++ )
            b = parseInt(b / 10);
        return a * 90 + b * 10 + 0;
    }
};

pmTarget = function(_options)
{
    var _trgt = this;
    this.options = $.extend(
    {
        promptText: "+ 接收频道"
    }, _options);

    this[0] = $('<div class="pm-border pm-mail-target">\
<table class="pm-layout-table"><tr>\
<input type="hidden" id="channel_id" name="channel_id" value="0"></input>\
<td class="pm-channel-logo"><div class="pm-avatar-img pm-avatar-small"><img src="css/logo/chanbgs.png"></div></td>\
<td class="pm-channel-add"><input type="text" class="pm-no-border" id="add-channel" value="+ 接收频道"></input></td>\
<td class="pm-channel-desc">&nbsp;</td><td>&nbsp;</td>\
</tr></table></div>');

    this.input = $('input#add-channel', _trgt[0]).unbind('focus').unbind('blur').autocomplete(
    {
        minLength: 0,

        select : function(event, ui)
        {
            var _uivc = ui.item.channel,
                _desc = _uivc.description;

            _desc = _desc.length > 20 ? _desc.substr(0, 20) + '...' : _desc;
            $('#channel_id', _trgt[0]).val(_uivc.ID);
            $('.pm-channel-desc', _trgt[0]).text(_desc);
            $('.pm-channel-logo img', _trgt[0]).attr('src', _uivc.logo.small);

            $('.pm-mail-weight', _trgt[0]).hide().show(function()
            {
                $('.pm-mail-weight input', _trgt[0]).focus();
            });
        },

        open: function(event, ui)
        {
            var _chns = $('.ui-autocomplete');
            $('.pm-border-line:last', _chns).remove();
            pm('.pm-dropdown-icon', _chns).loadImg();
            _chns.css({'min-width': (_trgt[0].width() - 48) + 'px'});
        }
    }).

    focus(function()
    {
        _trgt.empty();
        if( pmTarget.cache.length > 0 )
            _trgt.source();
        else
            _trgt.load();
    }).

    blur(function()
    {
        if( $(this).val() !== '' )
            return ;
        $(this).val(_trgt.options.promptText);
        _trgt.empty();
    });

    this.ruler = (new pmRuler()).ruler;
    this[0].append('<div class="pm-border-line"></div>').append(this.ruler);

    return this;
};

pmTarget.cache = [];

pmTarget.fn = pmTarget.prototype =
{
    empty : function()
    {
        this.ruler.hide();
        $('#channel-id', this.chan).val(0);
        $('.pm-channel-desc', this.chan).html('&nbsp;');
        $('.pm-channel-logo img', this.chan).attr('src', 'css/logo/chanbgs.png');
    },

    source : function()
    {
        this.input.autocomplete({source: pmTarget.cache.slice(0)}).data( "autocomplete" )._renderItem = function( ul, item )
        {
            return $( "<li></li>" ).data( "item.autocomplete", item )
                    .append( "<a>" + pm.renderStr(item.channel) + "</a>" ).appendTo( ul );
        };
        this.input.val('').autocomplete('search', '');
    },

    load : function()
    {
        var _chan = this;

        $.get('api/pmail.api.channel.php',
        {
            p: 'list'
        },

        function(data)
        {
            if( data.msg )
            {
                pm('<div>').tip({content: data.msg});
                return;
            }

            for( var i = 0; i < data.channels.length; i ++ )
            {
                pmTarget.cache[i] = {id: data.channels[i].ID, label: data.channels[i].name,
                    desc: data.channels[i].description, channel: data.channels[i]};
            }
            _chan.source();
//            $.cookie('pm-channel-cache', $.json_encode({user_id: $('#userid').val(), channels: pmTarget.cache}), {expires: 30});
        }, 'json');
    }
};

pmEdit = function(_owner, _options)
{
    this.options = $.extend(
    {
        custom : function(_edit){ $('#p', _edit).val('publish'); },
        check : function(){ return true; },
        success : function(){},
        error : function(data){ pm('<div>').tip({content: data}); }
    }, _options);

    this[0] = $('<form class="pm-mail-edit" method="post" action="mail">\
<input type="hidden" name="p" id="p" value="reply"/>\
<input type="hidden" name="parent" id="parent" value="0"/>\
\
<div class="pm-mail-content"><textarea id="content" name="content">邮件内容</textarea></div>\
\
<div class="pm-ctrl pm-mail-ctrl"><a id="cancel" class="pm-gray-button">取消</a><a id="ok" class="pm-light-button pm-publish">发送</a><input type="submit" value="发布"></input></div>\
</form>').appendTo(_owner);
    var _pedit = this, _edit  = this[0];

    this.target = new pmTarget();
    $('.pm-mail-content', _edit).before(this.target[0]);
    $('.pm-mail-weight', _edit).hide();
    pm('.pm-mail-weight input', _edit).promptText('\u8bbe\u7f6e\u4f18\u5148\u7ea7');

    $('.pm-mail-content', _edit).before('<div class="pm-theme-title pm-border"><textarea class="pm-no-border" name="title" id="title">邮件标题</textarea></div>');
    pm('.pm-theme-title #title', _edit).promptText();
    $('.pm-theme-title #title', _edit).autoResize(
    {
        extraSpace: 0,
        onResized: function()
        {
            $(this).parent().height($(this).height() + 5);
        }
    });

    _pedit.options.custom(_edit, _pedit);
    $(".pm-publish", _edit).click(function()
    {
        if( $(".pm-mail-content textarea", _edit).val() !== '' )
            _edit.submit();
        return false;
    });

    this.prompt =
    {
        title: $('.pm-theme-title #title', _edit).text(),
        content: $('.pm-mail-content #content', _edit).val()
    };
    $('.pm-mail-content #content', _edit).wysiwyg();

    _edit.submit(function()
    {
        var _rank = 0,
            _data =
            {
                p : $('#p', _edit).val(),
                parent  : $('#parent', _edit).val(),
                channel_id : $('.pm-mail-target #channel_id', _edit).val(),
                weight  : $('.pm-mail-weight input', _edit).val(),
                title   : $('.pm-theme-title #title', _edit).val(),
                content : $('.pm-mail-content #content', _edit).val(),
                goods   : new Array(),
                photos  : new Array()
            },
            _list = $('<div>' + _data.content + '</div>');

        $('.cart', _list).remove();
        _data.content = _list.html();
        $('.pm-object', _list).each(function()
        {
            $(this).attr('rank', _rank);
            _rank ++;
        });

        $('.pm-mail-good', _list).each(function()
        {
            _data.goods.push({id: $(this).attr('id'), rank: $(this).attr('rank')});
        });
        $('.pm-input-photo', _list).each(function()
        {
            _data.photos.push({id: $(this).attr('id'), rank: $(this).attr('rank')});
        });
        _pedit.data = _data;

        if( !_pedit.options.check(_edit, _pedit) )
            return false;

        $.post('api/pmail.api.mail.php', _data,

        function(data)
        {
            if( $(data).hasClass('pm-err') )
            {
                _pedit.options.error(data, _edit, _pedit);
                return;
            }

            _pedit.options.success(data, _edit, _pedit);
        }, 'html');

        return false;
    });

    return this;
};

pmEdit.fn = pmEdit.prototype =
{
    title: '', content: '', goods: [], photos: [],

    complete : function()
    {
        $('.pm-mail-content textarea', this[0]).val('');
        $('.pm-mail-weight input', this[0]).val(0);
        this.target.empty();
    }
};

pm.fn.extend(
{
    edit: function(_options)
    {
        this.edit = new pmEdit(this[0], _options);
        return this;
    },

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

	$.wysiwyg.createLink = function (object, url, title)
    {
		return object.each(function ()
        {
			var oWysiwyg = $(this).data("wysiwyg"), selection;

			if (!oWysiwyg) {
				return this;
			}

			if (!url || url.length === 0) {
				return this;
			}

			selection = oWysiwyg.getRangeText();
			// ability to link selected img - just hack
			var internalRange = oWysiwyg.getInternalRange();
			var isNodeSelected = false;
			if( internalRange && internalRange.extractContents )
            {
				var rangeContents = internalRange.cloneContents();
				if (rangeContents !== null && rangeContents.childNodes && rangeContents.childNodes.length>0)
					isNodeSelected = true;
			}

			if( (selection && selection.length > 0) || isNodeSelected )
            {
				if ($.browser.msie)
                {
					oWysiwyg.ui.focus();
				}
				oWysiwyg.editorDoc.execCommand("unlink", false, null);
				oWysiwyg.editorDoc.execCommand("createLink", false, url);
			}
            else
            {
				if (title)
                {
					oWysiwyg.insertHtml('<a href="'+url+'">'+title+'</a>');
				}
                else
                {
					if (oWysiwyg.options.messages.nonSelection)
						window.alert(oWysiwyg.options.messages.nonSelection);
				}
			}
			return this;
		});
	};

	$.wysiwyg.controls.link = function (Wysiwyg)
    {
        var selection = Wysiwyg.getRangeText(),
            _dialog = $('<div class="pm-link-dialog">\
<div class="pm-dialog-content">\
<div class="pm-link-text">连接文字：<input type="text" name="ltext" id="ltext" class="pm-border"/></div>\
<div class="pm-link-href">连接地址：<input type="text" name="lhref" id="lhref" class="pm-border" value="http://"/></div>\
</div>\
<div class="pm-dialog-foot pm-ctrl"><a id="cancel" class="pm-gray-button">取消</a><a id="ok" class="pm-light-button">确定</a></div>\
</div>'),

        a = {
            self: Wysiwyg.dom.getElement("a"), // link to element node
			href: "http://",
			title: "",
			target: ""
		};

		if (a.self)
        {
			a.href = a.self.href ? a.self.href : a.href;
			a.title = a.self.title ? a.self.title : "";
			a.target = a.self.target ? a.self.target : "";
		}

		if (a.self)
        {
            $('#lhref', _dialog).val(a.href);
		}

        if (selection && selection.length > 0)
        {
            $('#ltext', _dialog).val(selection);
        }

//		var img = Wysiwyg.dom.getElement("img");
//        $('.pm-link-img', _dialog).hide();
        pm(_dialog).dialog({modal: true, title: '插入连接'});

        $('#ok', _dialog).unbind('click').click(function()
        {
            var url = $('input#lhref', _dialog).val(), title = $('input#ltext', _dialog).val();

			if (a.self)
            {
                if ("string" === typeof (url))
                {
                    if (url.length > 0)
                    {
						// to preserve all link attributes
						$(a.self).attr("href", url).attr("title", title).attr("target", target);
					}
                    else
                    {
                        $(a.self).replaceWith(a.self.innerHTML);
					}
				}
			}
            else
            {
				if ($.browser.msie)
                {
                    Wysiwyg.ui.returnRange();
				}

				//Do new link element
//				selection = Wysiwyg.getRangeText();

				if (selection && selection.length > 0)
                {
                    if ($.browser.msie)
                    {
                        Wysiwyg.ui.focus();
					}

					if ("string" === typeof (url))
                    {
						if (url.length > 0)
                        {
                            Wysiwyg.editorDoc.execCommand("createLink", false, url);
						}
                        else
                        {
							Wysiwyg.editorDoc.execCommand("unlink", false, null);
                        }
					}

					a.self = Wysiwyg.dom.getElement("a");
					$(a.self).attr("href", url).attr("title", title).attr("target", '_blank');
				}
                else if (title && title.length > 0)
                {
                    Wysiwyg.insertHtml('<a href="' + url + '" title="' + title + '" target="_blank">' + title + '</a>');
                }
                else if (Wysiwyg.options.messages.nonSelection)
                {
                    pm('<div>').tip({mess: Wysiwyg.options.messages.nonSelection});
                    return false;
                }
			}

            $(Wysiwyg.editorDoc).trigger("editorRefresh.wysiwyg");
            Wysiwyg.saveContent();
            pm(_dialog).dialog("destroy");
        });
    };

	$.wysiwyg.controls.shopping = function(Wysiwyg)
    {
        var _dialog = $('<div class="pm-good-dialog">\
<div class="pm-dialog-content">\
<div class="pm-good-box"></div>\
<div class="pm-parse-ctrl">网购商品：<input type="text" name="url" id="url" class="pm-border" value="http://"/>&nbsp;</div>\
<div class="pm-error"></div>\
</div>\
<div class="pm-dialog-foot pm-ctrl"><a id="cancel" class="pm-gray-button">取消</a><a id="ok" class="pm-light-button">确定</a></div>\
</div>');

        var _good = pm('<div>').good().good, _parse = $('.pm-parse-ctrl', _dialog);

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
            Wysiwyg.insertHtml($('.pm-mail-good', _dialog).parent().html() + '&nbsp;');
            $(Wysiwyg.editorDoc).trigger("editorRefresh.wysiwyg");
            Wysiwyg.saveContent();
            pm(_dialog).dialog("destroy");
        });
    };

	/**
	 * Wysiwyg namespace: public properties and methods
	 */
	$.wysiwyg.controls.image = function(Wysiwyg)
    {
        var _id = 0,
            _dialog = $('<div class="pm-photo-dialog">\
<div class="pm-dialog-content">\
<div class="pm-photo-box"></div>\
<div class="pm-photo-param">图片标题：<input type="text" name="title" id="title" class="pm-border"/></div>\
<div class="pm-photo-param">图片连接：<input type="text" name="phref" id="phref" class="pm-border" value="http://"/></div>\
<div class="pm-upload-ctrl pm-local-photo">本地图片：<input type="file" id="pm-upload-img" name="pm-upload-img"/>&nbsp;<a class="pm-light-button" id="upload">上传</a><img class="uploading" src="css/images/loading.gif"/></div>\
<div class="pm-move-ctrl pm-web-photo">网络图片：<input type="text" name="url" id="url" class="pm-border" value="http://"/></div>\
</div>\
<div class="pm-dialog-foot pm-ctrl"><a id="cancel" class="pm-gray-button">取消</a><a id="ok" class="pm-light-button">确定</a></div>\
</div>'),
            _photo = $('<div class="pm-input-photo pm-object">\
<div class="title"></div>\
<img src="css/images/sample.png">\
</div>'),
            _loaded = function($photo)
            {
                _id = $photo.ID;
                _photo.attr('id', _id);
                $('img', _photo).attr('src', $photo.big);
                $('.pm-upload-ctrl, .pm-move-ctrl', _dialog).hide();
                $('.pm-photo-param', _dialog).show();
            };

        $('.pm-photo-box', _dialog).prepend(_photo).show();
        pm(_dialog).dialog({modal: true, title: '插入图片'});

        $('#ok', _dialog).unbind('click').click(function()
        {
            if( _id === 0 )
                return false;
            if( $('input#title', _dialog).val().length > 0 )
                $('.title', _photo).text($('input#title', _dialog).val());
            else
                $('.title', _photo).remove();
//            Wysiwyg.insertHtml('&nbsp;' + _photo.parent().html() + '&nbsp;');
            Wysiwyg.insertHtml(_photo.parent().html() + '&nbsp;');
            Wysiwyg.saveContent();
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
        });

		$(Wysiwyg.editorDoc).trigger("editorRefresh.wysiwyg");
    };
})(jQuery);

//<ul id="sortable" class="pm-object-list"></ul>\
//    pm('.pm-mail-content #content', this.edit).promptText('\u90ae\u4ef6\u5185\u5bb9');
//    $('.pm-mail-content #content', this.edit).autoResize({auto: false});
//    var _pedit  = this, _parse = $('.pm-good-parse-ctrl', this.edit);

//    if( _custom !== undefined && $.isFunction(_custom) )
//    {
//        _custom(_edit, _pedit);
//    }

//        $('.pm-object-list', _edit).empty().hide();
//        $('.pm-upload-photo-ctrl, .pm-good-parse-ctrl', _edit).hide();

//        else
//        {
//            pm('<div>').tip({mess: Wysiwyg.options.messages.nonSelection});
//            return false;
//        }

//            Wysiwyg.insertHtml('&nbsp;' + $('.pm-mail-good', _dialog).parent().html() + '&nbsp;');
//<div class="photo"><img src="css/images/sample.png"></div>\
//    edit: function(_submit, _custom)
//    {
//        this.edit = new pmEdit(this[0], _submit, _custom);
//        return this;
//    }

//        _pedit.goods = new Array();
//        $('.pm-mail-good', _list).each(function()
//        {
//            _pedit.goods.push({id: $(this).attr('id'), rank: $(this).attr('rank')});
//        });
//        _pedit.photos = new Array();
//        $('.pm-input-photo', _list).each(function()
//        {
//            _pedit.photos.push({id: $(this).attr('id'), rank: $(this).attr('rank')});
//        });

//        _pedit.process = $('#p', this).val();
//        _pedit.parent = $('#parent', this).val();
//        _pedit.chan_id = $('.pm-mail-target #channel_id', this).val();
//        _pedit.weight = $('.pm-mail-weight input', this).val();
//        _pedit.title = $('.pm-theme-title #title', this).val();
//        _pedit.content = _cont; //$('.pm-mail-content #content', this).val();

//        var _rank = 0, _list = $('.pm-object-list', this);
//        $('.pm-object', _list).each(function()
//        {
//            $(this).attr('rank', _rank);
//            _rank ++;
//        });
//
//        _pedit.goods = new Array();
//        $('.pm-mail-good', _list).each(function()
//        {
//            _pedit.goods.push({id: $('#good_id', this).val(), desc: $('#good_desc', this).val(), rank: $(this).attr('rank')});
//        });
//        _pedit.photos = new Array();
//        $('.pm-input-photo', _list).each(function()
//        {
//            _pedit.photos.push({id: $('#photo_id', this).val(), desc: $('#photo_desc', this).val(),
//                rank: $(this).attr('rank')});
//        });
//
//        _pedit.parent = $('#parent', this).val();
//        _pedit.chan_id = $('.pm-mail-target #channel_id', this).val();
//        _pedit.weight = $('.pm-mail-weight input', this).val();
//        _pedit.title = $('.pm-theme-title #title', this).val();
//        _pedit.content = $('.pm-mail-content #content', this).val();
//
//        if( _submit !== undefined && $.isFunction(_submit) )
//            _submit($(this), _pedit);
//        return false;

//    this.ruler = (new pmRuler()).ruler;
//    this[0] = $('<div class="pm-border pm-mail-target">\
//<table class="pm-layout-table"><tr>\
//<input type="hidden" id="channel_id" name="channel_id" value="0"></input>\
//<td class="pm-channel-logo"><div class="pm-avatar-img pm-avatar-small"><img src="css/logo/chanbgs.png"></div></td>\
//<td class="pm-channel-add"><input type="text" class="pm-no-border" id="add-channel" value="+ 接收频道"></input></td>\
//<td class="pm-channel-desc">&nbsp;</td><td>&nbsp;</td>\
//</tr></table></div>').append('<div class="pm-border-line"></div>').append(this.ruler);

//				img = Wysiwyg.dom.getElement("img");
//				if ((selection && selection.length > 0) || img) {
//                    if ($.browser.msie) {
//                        Wysiwyg.ui.focus();
//					}
//
//					if ("string" === typeof (url)) {
//						if (url.length > 0) {
//                            Wysiwyg.editorDoc.execCommand("createLink", false, url);
//						} else {
//							Wysiwyg.editorDoc.execCommand("unlink", false, null);
//                        }
//					}
//
//					a.self = Wysiwyg.dom.getElement("a");
//					$(a.self).attr("href", url).attr("title", title).attr("target", '_blank');
//				}

//		if (a.self)
//        {
//            $('#lhref', _dialog).val(a.href);
//alert(a.self.innerHTML);
////            url = window.prompt("URL", a.href);
////
////            if ("string" === typeof (url))
////            {
////                if (url.length > 0)
////                {
////                    // to preserve all link attributes
////					$(a.self).attr("href", url).attr("title", title).attr("target", target);
////				}
////                else
////                {
////					$(a.self).replaceWith(a.self.innerHTML);
////				}
////			}
//		}
//        else
//        {
//            //Do new link element
////            selection = Wysiwyg.getRangeText();
////            img = Wysiwyg.dom.getElement("img");
//
//            if (selection && selection.length > 0)
//            {
//                $('#ltext', _dialog).val(selection);
////                $('.pm-link-img', _dialog).hide();
//            }
////            else if( img )
////            {
////                $('#limg', _dialog).attr('src', img);
////                $('.pm-link-text', _dialog).hide();
////            }
//            else if (Wysiwyg.options.messages.nonSelection)
//            {
//                pm('<div>').tip({mess: Wysiwyg.options.messages.nonSelection});
//                return false;
//			}
//		}

//		if (Wysiwyg.options.controlLink.forceRelativeUrls) {
//            baseUrl = window.location.protocol + "//" + window.location.hostname;
//			if (0 === url.indexOf(baseUrl)) {
//                url = url.substr(baseUrl.length);
//			}
//		}

//			if ((selection && selection.length > 0) || img)
//            {
//                if ($.browser.msie) {
//                    Wysiwyg.ui.focus();
//					Wysiwyg.editorDoc.execCommand("createLink", true, null);
//				} else {
//							url = window.prompt(dialogReplacements.url, a.href);
//
//							if (Wysiwyg.options.controlLink.forceRelativeUrls) {
//								baseUrl = window.location.protocol + "//" + window.location.hostname;
//								if (0 === url.indexOf(baseUrl)) {
//									url = url.substr(baseUrl.length);
//								}
//							}
//
//							if ("string" === typeof (url)) {
//								if (url.length > 0) {
//									Wysiwyg.editorDoc.execCommand("createLink", false, url);
//								} else {
//									Wysiwyg.editorDoc.execCommand("unlink", false, null);
//								}
//							}
//				}
//			} else if (Wysiwyg.options.messages.nonSelection) {
//                window.alert(Wysiwyg.options.messages.nonSelection);
//			}
////            url =
//
//            if ((selection && selection.length > 0) || img) {
//                if ($.browser.msie) {
//                    Wysiwyg.ui.focus();
//                }
//
//                if ("string" === typeof (url)) {
//                    if (url.length > 0) {
//                        Wysiwyg.editorDoc.execCommand("createLink", false, url);
//                    } else {
//                        Wysiwyg.editorDoc.execCommand("unlink", false, null);
//                    }
//                }
//
//                a.self = Wysiwyg.dom.getElement("a");
//
//                $(a.self).attr("href", url).attr("title", title);
//
//                /**
//                 * @url https://github.com/akzhan/jwysiwyg/issues/16
//                 */
//                $(a.self).attr("target", target);
//            } else if (Wysiwyg.options.messages.nonSelection) {
//                window.alert(Wysiwyg.options.messages.nonSelection);
//			}

//alert(selection);
//        a = {
//				self: Wysiwyg.dom.getElement("a"), // link to element node
//				href: "http://",
//				title: ""
//			};
//
//		if (a.self)
//        {
//			a.href = a.self.href ? a.self.href : a.href;
//			a.title = a.self.title ? a.self.title : "";
//			a.target = a.self.target ? a.self.target : "";
//            $('#ltext', _dialog).val(a.title);
//            $('#lhref', _dialog).val(a.href);
//		}

//	$.wysiwyg.controls.shopping =
//    {
//        init : function(Wysiwyg)
//        {
//            var _dialog = $('<div class="pm-good-dialog">\
//<div class="pm-dialog-content">\
//<div class="pm-good-box"></div>\
//<div class="pm-parse-ctrl">网购商品：<input type="text" name="url" id="url" class="pm-border" value="http://"/>&nbsp;</div>\
//<div class="pm-error"></div>\
//</div>\
//<div class="pm-dialog-foot pm-ctrl"><a id="cancel" class="pm-gray-button">取消</a><a id="ok" class="pm-light-button">确定</a></div>\
//</div>');
//
//            var _good = new pmGood('<div>'),
//                _parse = $('.pm-parse-ctrl', _dialog);
//
////  http://detail.tmall.com/item.htm?spm=3.358051.300174.3.AFdWEY&id=16174657337
//            var _pFunc = function()
//            {
//                if( $('#url', _parse).val() === "" )
//                    return;
//                _good.parse($('#url', _parse).val(), _pOption);
//            };
//
//            var _pOption =
//            {
//                start: function()
//                {
//                    pm(_parse).tipParsing('\u6b63\u5728\u89e3\u6790...');
//                },
//
//                end : function()
//                {
//                    pm(_parse).removeParsing();
//                    $('#url', _parse).val('');
//                },
//
//                error : function(_text)
//                {
//                    $(".pm-error", _dialog).html(_text).show();
//                    pm(_dialog).dialog("show");
//                },
//
//                success: function()
//                {
//                    _parse.hide();
//                    $('.pm-good-box', _dialog).append(_good[0]).show();
//                    pm(_dialog).dialog("show");
//                }
//            };
//
//            $(".pm-error", _dialog).hide();
//            pm(_dialog).dialog({modal: true, title: '插入网购商品'});
//
//            $("#url", _dialog).focus(function()
//            {
//                $(".pm-error", _dialog).hide();
//            }).
//            bind('keyup', function(event)
//            {
//                if( event.keyCode === 13 )
//                {
//                    _pFunc();
//                }
//            }).
//            change(_pFunc);
//
//            $('#ok', _dialog).unbind('click').click(function()
//            {
//                if( _good.id === 0 )
//                    return false;
//
//                Wysiwyg.insertHtml('&nbsp;' + $('.pm-mail-good', _dialog).parent().html() + '&nbsp;');
//                $(Wysiwyg.editorDoc).trigger("editorRefresh.wysiwyg");
//                pm(_dialog).dialog("destroy");
//            });
//        }
//    };

//	$.wysiwyg.controls.image =
//    {
//        init : function(Wysiwyg)
//        {
//            var _id = 0,
//                _dialog = $('<div class="pm-photo-dialog">\
//<div class="pm-dialog-content">\
//<div class="pm-photo-box"></div>\
//<div class="pm-photo-param">图片标题：<input type="text" name="title" id="title" class="pm-border"/></div>\
//<div class="pm-upload-ctrl pm-local-photo">本地图片：<input type="file" id="pm-upload-img" name="pm-upload-img"/>&nbsp;<a class="pm-light-button" id="upload">上传</a><img class="uploading" src="css/images/loading.gif"/></div>\
//<div class="pm-move-ctrl pm-web-photo">网络图片：<input type="text" name="url" id="url" class="pm-border" value="http://"/></div>\
//</div>\
//<div class="pm-dialog-foot pm-ctrl"><a id="cancel" class="pm-gray-button">取消</a><a id="ok" class="pm-light-button">确定</a></div>\
//</div>'),
//                _photo = $('<div class="pm-input-photo">\
//<div class="title"></div>\
//<div class="photo"><img src="css/images/sample.png"></div>\
//</div>'),
//                _loaded = function($photo)
//            {
//                _id = $photo.ID;
//                $('.photo img', _photo).attr('src', $photo.big);
//                $('.pm-upload-ctrl, .pm-move-ctrl', _dialog).hide();
//                $('.pm-photo-param', _dialog).show();
//            };
//
//            $('.pm-photo-box', _dialog).prepend(_photo).show();
//            pm(_dialog).dialog({modal: true, title: '插入图片'});
//
//            $('#ok', _dialog).unbind('click').click(function()
//            {
//                if( _id === 0 )
//                    return false;
//
//                $('.title', _photo).text($('input#title', _dialog).val());
//                Wysiwyg.insertHtml('&nbsp;' + _photo.parent().html() + '&nbsp;');
//                pm(_dialog).dialog("destroy");
//            });
//            $('#cancel', _dialog).click(function()
//            {
//                if( _id === 0 )
//                    return false;
//                $.get('api/pmail.api.photo.php', { p : 'delete', id : _id });
//            });
//
//            $('#upload', _dialog).click(function()
//            {
//                pm.upload(_dialog, {loaded : _loaded});
//            });
//            $('#url', _dialog).change(function()
//            {
//                pm.move(_dialog, {loaded : _loaded});
//            });
//
//			$(Wysiwyg.editorDoc).trigger("editorRefresh.wysiwyg");
//        }
//    };

//$(function()
//{
//    $('#wysiwyg').wysiwyg();
//});