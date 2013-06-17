/*!
 * DINSTR项目 JavaScript v1.1.2
 *
 * @bref 邮件编辑器
 * @author 朱继玉<zhuhz82@126.com>
 * @copyright @2013 有向信息流
 */
disRuler = function ()
{
    this.ruler = $('<div class="dis-info-weight">\
<input type="text" name="weight" class="dis-no-border" value="设置优先级"></input>\
<div class="dis-ruler dis-inline-block" title="">\
<div class="dis-tag-list"></div>\
<div class="dis-mark-list"><div class="dis-first-mark dis-inline-block"></div></div>\
<div class="dis-ruler-line"></div>\
</div></div>');

    for( var i = 0, _txt = 1; i < 5; i ++ )
    {
        $('.dis-mark-list', this.ruler).append('<div class="dis-mark dis-inline-block"></div>');
        $('<div class="dis-tag dis-inline-block"></div>').text(_txt).appendTo($('.dis-tag-list', this.ruler));
        _txt *= 10;
    }
    $('.dis-tag:first', this.ruler).text(1);

    var _ruler = this;
    $('input', this.ruler).blur(function()
    {
        var _val = _ruler.unPreg($(this).val());
        $('.dis-ruler-line', _ruler.ruler).width(_val);
        $(this).val(_ruler.preg(_val));
    });

    $('.dis-ruler', this.ruler).mousedown(function()
    {
        $('input', $(this).parent()).val($(this).attr('title'));
        $('.dis-ruler-line', _ruler.ruler).width(_ruler.unPreg($(this).attr('title')));
    }).

    mousemove(function(e)
    {
        var _left = e.clientX - $(this).offset().left;
        $(this).attr('title', _ruler.preg(_left));
    });

    return this;
};

disRuler.fn = disRuler.prototype =
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

disTarget = function(_options)
{
    var _trgt = this;
    this.options = $.extend(
    {
        promptText: "+ 接收频道"
    }, _options);

    this[0] = $('<div class="dis-border dis-info-target">\
<table class="dis-layout-table"><tr>\
<input type="hidden" id="channel_id" name="channel_id" value="0"></input>\
<td class="dis-channel-logo"><div class="dis-avatar-img dis-avatar-small"><img src="css/logo/chanbgs.png"></div></td>\
<td class="dis-channel-add"><input type="text" class="dis-no-border" id="add-channel" value="+ 接收频道"></input></td>\
<td class="dis-channel-desc">&nbsp;</td><td>&nbsp;</td>\
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
            $('.dis-channel-desc', _trgt[0]).text(_desc);
            $('.dis-channel-logo img', _trgt[0]).attr('src', _uivc.logo.small);

            $('.dis-info-weight', _trgt[0]).hide().show(function()
            {
                $('.dis-info-weight input', _trgt[0]).focus();
            });
        },

        open: function(event, ui)
        {
            var _chns = $('.ui-autocomplete');
            $('.dis-border-line:last', _chns).remove();
            pm('.dis-dropdown-icon', _chns).loadImg();
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
    this[0].append('<div class="dis-border-line"></div>').append(this.ruler);

    return this;
};

disTarget.cache = [];

disTarget.fn = disTarget.prototype =
{
    empty : function()
    {
        this.ruler.hide();
        $('#channel-id', this.chan).val(0);
        $('.dis-channel-desc', this.chan).html('&nbsp;');
        $('.dis-channel-logo img', this.chan).attr('src', 'css/logo/chanbgs.png');
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

        $.get('api/chan.api.php',
        {
            p: 'list'
        },

        function(data)
        {
            if( data.msg )
            {
                dis('<div>').tip({content: data.msg});
                return;
            }

            for( var i = 0; i < data.channels.length; i ++ )
            {
                disTarget.cache[i] = {id: data.channels[i].ID, label: data.channels[i].name,
                    desc: data.channels[i].description, channel: data.channels[i]};
            }
            _chan.source();
//            $.cookie('dis-channel-cache', $.json_encode({user_id: $('#userid').val(), channels: pmTarget.cache}), {expires: 30});
        }, 'json');
    }
};

disEdit = function(_owner, _options)
{
    this.options = $.extend(
    {
        custom : function(_edit){ $('#p', _edit).val('publish'); },
        check : function(){ return true; },
        success : function(){},
        error : function(data){ dis('<div>').tip({content: data}); }
    }, _options);

    this[0] = $('<form class="dis-info-edit" method="post" action="mail">\
<input type="hidden" name="p" id="p" value="reply"/>\
<input type="hidden" name="parent" id="parent" value="0"/>\
\
<div class="dis-info-content"><textarea id="content" name="content">邮件内容</textarea></div>\
\
<div class="dis-ctrl dis-info-ctrl"><a id="cancel" class="dis-gray-button">取消</a><a id="ok" class="dis-light-button dis-publish">发送</a><input type="submit" value="发布"></input></div>\
</form>').appendTo(_owner);
    var _pedit = this, _edit  = this[0];

    this.target = new pmTarget();
    $('.dis-info-content', _edit).before(this.target[0]);
    $('.dis-info-weight', _edit).hide();
    dis('.dis-info-weight input', _edit).promptText('\u8bbe\u7f6e\u4f18\u5148\u7ea7');

    $('.dis-info-content', _edit).before('<div class="dis-theme-title dis-border"><textarea class="dis-no-border" name="title" id="title">邮件标题</textarea></div>');
    dis('.dis-theme-title #title', _edit).promptText();
    $('.dis-theme-title #title', _edit).autoResize(
    {
        extraSpace: 0,
        onResized: function()
        {
            $(this).parent().height($(this).height() + 5);
        }
    });

    _pedit.options.custom(_edit, _pedit);
    $(".dis-publish", _edit).click(function()
    {
        if( $(".dis-info-content textarea", _edit).val() !== '' )
            _edit.submit();
        return false;
    });

    this.prompt =
    {
        title: $('.dis-theme-title #title', _edit).text(),
        content: $('.dis-info-content #content', _edit).val()
    };
    $('.dis-info-content #content', _edit).wysiwyg();

    _edit.submit(function()
    {
        var _rank = 0,
            _data =
            {
                p : $('#p', _edit).val(),
                parent  : $('#parent', _edit).val(),
                channel_id : $('.dis-info-target #channel_id', _edit).val(),
                weight  : $('.dis-info-weight input', _edit).val(),
                title   : $('.dis-theme-title #title', _edit).val(),
                content : $('.dis-info-content #content', _edit).val(),
                goods   : new Array(),
                photos  : new Array()
            },
            _list = $('<div>' + _data.content + '</div>');

        $('.cart', _list).remove();
        _data.content = _list.html();
        
        $('.dis-object', _list).each(function()
        {
            $(this).attr('rank', _rank);
            _rank ++;
        });

        $('.dis-info-good', _list).each(function()
        {
            _data.goods.push({id: $(this).attr('id'), rank: $(this).attr('rank')});
        });
        $('.dis-input-photo', _list).each(function()
        {
            _data.photos.push({id: $(this).attr('id'), rank: $(this).attr('rank')});
        });
        _pedit.data = _data;

        if( !_pedit.options.check(_edit, _pedit) )
            return false;

        $.post('api/info.api.php', _data,

        function(data)
        {
            if( $(data).hasClass('dis-err') )
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

disEdit.fn = disEdit.prototype =
{
    title: '', content: '', goods: [], photos: [],

    complete : function()
    {
        $('.dis-info-content textarea', this[0]).val('');
        $('.dis-info-weight input', this[0]).val(0);
        this.target.empty();
    }
};

dis.fn.extend(
{
    edit: function(_options)
    {
        this.edit = new disEdit(this[0], _options);
        return this;
    },

    tipParsing : function (str)
    {
        var _load = $('<div id="parsing" class="dis-load"><div class="ui-widget-overlay"></div></div>')
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
            _dialog = $('<div class="dis-link-dialog">\
<div class="dis-dialog-content">\
<div class="dis-link-text">连接文字：<input type="text" name="ltext" id="ltext" class="dis-border"/></div>\
<div class="dis-link-href">连接地址：<input type="text" name="lhref" id="lhref" class="dis-border" value="http://"/></div>\
</div>\
<div class="dis-dialog-foot dis-ctrl"><a id="cancel" class="dis-gray-button">取消</a><a id="ok" class="dis-light-button">确定</a></div>\
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

        dis(_dialog).dialog({modal: true, title: '插入连接'});

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
                    dis('<div>').tip({mess: Wysiwyg.options.messages.nonSelection});
                    return false;
                }
			}

            $(Wysiwyg.editorDoc).trigger("editorRefresh.wysiwyg");
            Wysiwyg.saveContent();
            dis(_dialog).dialog("destroy");
        });
    };

	$.wysiwyg.controls.shopping = function(Wysiwyg)
    {
        var _dialog = $('<div class="dis-good-dialog">\
<div class="dis-dialog-content">\
<div class="dis-good-box"></div>\
<div class="dis-parse-ctrl">网购商品：<input type="text" name="url" id="url" class="dis-border" value="http://"/>&nbsp;</div>\
<div class="dis-error"></div>\
</div>\
<div class="dis-dialog-foot dis-ctrl"><a id="cancel" class="dis-gray-button">取消</a><a id="ok" class="dis-light-button">确定</a></div>\
</div>');

        var _good = dis('<div>').good().good, _parse = $('.dis-parse-ctrl', _dialog);

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
                $(".dis-error", _dialog).html(_text).show();
                dis(_dialog).dialog("show");
            },

            success: function()
            {
                _parse.hide();
                $('.dis-good-box', _dialog).append(_good[0]).show();
                dis(_dialog).dialog("show");
            }
        };

        $(".dis-error", _dialog).hide();
        dis(_dialog).dialog({modal: true, title: '插入网购商品'});

        $("#url", _dialog).focus(function()
        {
            $(".dis-error", _dialog).hide();
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
            Wysiwyg.insertHtml($('.dis-info-good', _dialog).parent().html() + '&nbsp;');
            $(Wysiwyg.editorDoc).trigger("editorRefresh.wysiwyg");
            Wysiwyg.saveContent();
            dis(_dialog).dialog("destroy");
        });
    };

	$.wysiwyg.controls.image = function(Wysiwyg)
    {
        var _id = 0,
            _dialog = $('<div class="dis-photo-dialog">\
<div class="dis-dialog-content">\
<div class="dis-photo-box"></div>\
<div class="dis-photo-param">图片标题：<input type="text" name="title" id="title" class="dis-border"/></div>\
<div class="dis-photo-param">图片连接：<input type="text" name="phref" id="phref" class="dis-border" value="http://"/></div>\
<div class="dis-upload-ctrl dis-local-photo">本地图片：<input type="file" id="dis-upload-img" name="dis-upload-img"/>&nbsp;<a class="dis-light-button" id="upload">上传</a><img class="uploading" src="css/images/loading.gif"/></div>\
<div class="dis-move-ctrl dis-web-photo">网络图片：<input type="text" name="url" id="url" class="dis-border" value="http://"/></div>\
</div>\
<div class="dis-dialog-foot dis-ctrl"><a id="cancel" class="dis-gray-button">取消</a><a id="ok" class="dis-light-button">确定</a></div>\
</div>'),
            _photo = $('<div class="dis-input-photo dis-object">\
<div class="title"></div>\
<img src="css/images/sample.png">\
</div>'),
            _loaded = function($photo)
            {
                _id = $photo.ID;
                _photo.attr('id', _id);
                $('img', _photo).attr('src', $photo.big);
                $('.dis-upload-ctrl, .dis-move-ctrl', _dialog).hide();
                $('.dis-photo-param', _dialog).show();
            };

        $('.dis-photo-box', _dialog).prepend(_photo).show();
        dis(_dialog).dialog({modal: true, title: '插入图片'});

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
            dis(_dialog).dialog("destroy");
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

//$(function()
//{
//    $('#wysiwyg').wysiwyg();
//});
