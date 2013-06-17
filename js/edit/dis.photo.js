/*！
 * 上传文件操作
 * 在使用在线工具http://dean.edwards.name/packer/压缩本文件代码时，
 * 只选Base62 encode选项，不要选Shrink variables
 */
$.extend(
{
    //create frame
    createUploadIframe: function(id, uri)
    {
        var io, frameId = 'jUploadFrame' + id;

        if( window.ActiveXObject )
        {
            io = document.createElement('<iframe id="' + frameId + '" name="' + frameId + '" />');
            if(typeof uri === 'boolean')
            {
                io.src = 'javascript:false';
            }
            else if(typeof uri === 'string')
            {
                io.src = uri;
            }
        }
        else
        {
            io = document.createElement('iframe');
            io.id = frameId;
            io.name = frameId;
        }

        io.style.position = 'absolute';
        io.style.top = '-1000px';
        io.style.left = '-1000px';

        document.body.appendChild(io);
        return io;
    },

    //create form
    createUploadForm: function(id, fileElement)
    {
        var formId = 'jUploadForm' + id;
        var fileId = 'jUploadFile' + id;
        var form = $('<form action="" method="POST" name="' + formId + '" id="' + formId + '" enctype="multipart/form-data"></form>');
        var newElement = fileElement.clone();

        fileElement.before(newElement);
        fileElement.attr('id', fileId).appendTo(form);
        //set attributes
        form.appendTo($(document.body)).css({'position': 'absolute', 'top': '-1200px', 'left': '-1200px'});
        return form;
    },

    ajaxFileUpload: function(s)
    {
        // TODO introduce global settings, allowing the client to modify them for all requests, not only timeout
        s = $.extend({}, $.ajaxSettings, s);

        var _date = new Date();
        var id = _date.getTime();
        var form = $.createUploadForm(id, s.fileElement);
        var io = $.createUploadIframe(id, s.secureuri);
        var frameId = 'jUploadFrame' + id;
        //var formId = 'jUploadForm' + id;

        $('<input type="hidden" name="p" id="p"></input>').val(s.p).prependTo(form);

        // Watch for a new set of requests
        if( s.global && ! $.active ++ )
        {
            $.event.trigger( "ajaxStart" );
        }

        var requestDone = false;
        // Create the request object
        var xml = {};
        if( s.global )
            $.event.trigger("ajaxSend", [xml, s]);

        // Wait for a response to come back
        var uploadCallback = function(isTimeout)
        {
            //var io = document.getElementById(frameId);
            try
            {
                if( io.contentWindow )
                {
                    xml.responseText = io.contentWindow.document.body ? io.contentWindow.document.body.innerHTML : null;
                    xml.responseXML = io.contentWindow.document.XMLDocument ? io.contentWindow.document.XMLDocument : io.contentWindow.document;
                }
                else if( io.contentDocument )
                {
                    xml.responseText = io.contentDocument.document.body ? io.contentDocument.document.body.innerHTML : null;
                    xml.responseXML = io.contentDocument.document.XMLDocument ? io.contentDocument.document.XMLDocument : io.contentDocument.document;
                }
            }
            catch(e)
            {
                $.handleError(s, xml, null, e);
            }

            if( xml || isTimeout === "timeout")
            {
                var status;
                requestDone = true;

                try
                {
                    status = (isTimeout !== "timeout") ? "success" : "error";
                    // Make sure that the request was successful or notmodified
                    if( status !== "error" )
                    {
                        // process the data (runs the xml through httpData regardless of callback)
                        var data = $.uploadHttpData( xml, s.dataType );
                        // If a local callback was specified, fire it and pass it the data
                        if( s.success )
                            s.success( data, status );

                        // Fire the global callback
                        if( s.global )
                            $.event.trigger( "ajaxSuccess", [xml, s] );
                    }
                    else
                        $.handleError(s, xml, status);
                }
                catch(e)
                {
                    status = "error";
                    $.handleError(s, xml, status, e);
                }

                // The request was completed
                if( s.global )
                    $.event.trigger( "ajaxComplete", [xml, s] );

                // Handle the global AJAX counter
                if( s.global && ! --$.active )
                    $.event.trigger( "ajaxStop" );

                // Process result
                if( s.complete )
                    s.complete(xml, status);

                $(io).unbind();

                setTimeout(function()
                {
                    try
                    {
                        $(io).remove();
                        $(form).remove();
                    }
                    catch(e)
                    {
                        $.handleError(s, xml, null, e);
                    }
                }, 100);
                xml = null;
            }
        };

        // Timeout checker
        if( s.timeout > 0 )
        {
            setTimeout(function()
            {
                // Check to see if the request is still happening
                if( !requestDone )
                    uploadCallback( "timeout" );
            }, s.timeout);
        }

        try
        {
            form.attr('action', s.url );
            form.attr('method', 'POST');
            form.attr('target', frameId);

            if( form.encoding )
            {
                form.encoding = 'multipart/form-data';
            }
            else
            {
                form.enctype = 'multipart/form-data';
            }
            form.submit();
        }
        catch(e)
        {
            $.handleError(s, xml, null, e);
        }

        if( window.attachEvent )
        {
            document.getElementById(frameId).attachEvent('onload', uploadCallback);
        }
        else
        {
            document.getElementById(frameId).addEventListener('load', uploadCallback, false);
        }
        return {abort: function () {}};
    },

    uploadHttpData: function( r, type )
    {
        var data = !type;
        data = (type === "xml" || data) ? r.responseXML : r.responseText;

        // If the type is "script", eval it in global context
        try
        {
            if( type === "script" )
                $.globalEval( data );
            // Get the JavaScript object, if JSON is used.
            if( type === "json" )
                eval( "data = " + data );
            // evaluate scripts within html
            if( type === "html" )
                $("<div>").html(data).evalScripts();
            //alert($('param', data).each(function(){alert($(this).attr('value'));}));
        }
        catch(e)
        {
            alert(e);
        }
        return data;
    }
});

/*!
 * DINSTR项目 JavaScript v1.0.0
 * 编辑博客
 * 该文件依赖 dis.core.js ajaxuploadfile.js
 *
 * @bref 编辑博客操作
 * @author 朱继玉<zhuhz82@126.com>
 * @copyright @2013 有向信息流
 */
_dis.upload = _dis.Upload = function(_photo, _options)
{
    var $options = $.extend(
    {
        p: 'upload',
        file: $('#dis-upload-img', _photo).val(),
        loaded: function(){},
        avatar: null
    }, _options);

    if( $options.file === '' )
    {
        dis('<div>').tip({mess: '请先选择一张本地图片！'});
        return false;
    }

    $("img.uploading", _photo).ajaxStart(function()
    {
        $(this).show();
        $(this).unbind('ajaxStart');
    }).
    ajaxComplete(function()
    {
        $(this).hide();
        $(this).unbind('ajaxComplete');
    });

    $.ajaxFileUpload(
    {
        p: $options.p,
        url: 'api/photo.api.php',
        secureuri: false,
        dataType: 'json',
        fileElementId:  'dis-upload-img',
        fileElement: $('#dis-upload-img', _photo),

        success: function (data)
        {
            if( data.error )
            {
                dis('<div>').tip({ mess: data.error });
                return;
            }

            if( $options.avatar )
                dis.avatar(data.photo, $options.avatar);
            $options.loaded(data.photo);
        },

        error: function (data, status, e)
        {
            alert(e);
        }
    });

    return false;
};

_dis.avatar = disAvatar = function(photo, _adjusted)
{
    var _photo = $.extend({ID: 0, big: '', small: ''}, photo);
    var _dialg = $('<div class="dis-avatar-dialog">\
<div class="dis-dialog-content"></div>\
<div class="dis-dialog-foot"><div class="dis-ctrl"><a id="cancel" class="dis-gray-button">取消</a><a id="ok" class="dis-light-button">确定</a></div></div>\
</div>');
    
    var _edit = $('<div class="dis-avatar-edit">\
<div class="dis-inline-block dis-avatar-adjust"></div>\
<div class="dis-inline-block dis-avatar-result">\
<div class="dis-avatar-new dis-big dis-avatar-img"><img ></div>\
<div>大尺寸头像 100X100像素</div>\
<div class="dis-avatar-new dis-middle dis-avatar-img"><img ></div>\
<div>中尺寸头像 50 X 50像素</div>\
<div class="dis-avatar-new dis-small dis-avatar-img"><img ></div>\
<div>中尺寸头像 30 X 30像素</div>\
</div></div>').appendTo($('.dis-dialog-content', _dialg));

    $('<div class="dis-avatar-source"><img >\
<table class="dis-overlay dis-layout-table">\
<tr class="top">\
<td class="left"><div class="ui-widget-overlay dis-opacity-65"></div></td>\
<td class="center"><div class="ui-widget-overlay dis-opacity-65"></div></td>\
<td class="right"><div class="ui-widget-overlay dis-opacity-65"></div></td>\n\
</tr><tr class="middle">\
<td class="left"><div class="ui-widget-overlay dis-opacity-65"></div></td>\
<td class="center">                                                   </td>\
<td class="right"><div class="ui-widget-overlay dis-opacity-65"></div></td>\n\
</tr><tr class="bottom">\
<td class="left"><div class="ui-widget-overlay dis-opacity-65"></div></td>\
<td class="center"><div class="ui-widget-overlay dis-opacity-65"></div></td>\
<td class="right"><div class="ui-widget-overlay dis-opacity-65"></div></td>\n\
</tr>\
</table>\
<div id="adjust"></div></div>').appendTo($('.dis-avatar-adjust', _edit));
    $('.dis-avatar-source img', _edit).hide().attr('src', _photo.big);
    
    dis(_dialg).dialog({modal: true});

    var _big = $('.dis-big', _edit), _mid = $('.dis-middle', _edit), _sml = $('.dis-small', _edit);
    var _dst = {left: 0, top: 0, width: 200, height: 200};
    var _src = {width: 0, height: 0};

    _big.width(150).height(150);
    _mid.width(50) .height(50) ;
    _sml.width(30) .height(30) ;

    $('img', _big).attr('src', $('.dis-avatar-source img', _edit).attr('src'));
    $('img', _mid).attr('src', $('.dis-avatar-source img', _edit).attr('src'));
    $('img', _sml).attr('src', $('.dis-avatar-source img', _edit).attr('src'));

    $('.dis-avatar-source img', _edit).fadeIn('slow', function()
    {
        _src.width  = $(this).width() ;
        _src.height = $(this).height();

        var _min = Math.min(_src.width, _src.height);
        _dst.width  = Math.min(_dst.width , _min);
        _dst.height = Math.min(_dst.height, _min);

        $('.dis-avatar-source .dis-overlay', _edit).show().css('z-index', '21');
        disAvatar.Overlay(_edit, _dst, _src);

        disAvatar.Adjust(_dst, _src, _big);
        disAvatar.Adjust(_dst, _src, _mid);
        disAvatar.Adjust(_dst, _src, _sml);
        dis(_dialg).dialog('show');
    });

    $('#adjust', _edit).width(_dst.width).height(_dst.height).draggable(
    {
        drag: function()
        {
            _dst.left = $(this).offset().left - _edit.offset().left;
            _dst.top  = $(this).offset().top  - _edit.offset().top ;
            disAvatar.Overlay(_edit, _dst, _src);

            disAvatar.Adjust(_dst, _src, _big);
            disAvatar.Adjust(_dst, _src, _mid);
            disAvatar.Adjust(_dst, _src, _sml);
        },

        containment: 'parent'
    }).

    resizable(
    {
        resize: function()
        {
            _dst.width  = $(this).width() ;
            _dst.height = $(this).height();
            disAvatar.Overlay(_edit, _dst, _src);

            disAvatar.Adjust(_dst, _src, _big);
            disAvatar.Adjust(_dst, _src, _mid);
            disAvatar.Adjust(_dst, _src, _sml);
        },
        aspectRatio: 1
    });

//    $('.pm-ctrl #cancel', _dialg).click(function()
//    {
//        _pmDialog.dialog('destroy');
//        pm(_dialg).dialog('destroy');
//    });

    $('.dis-ctrl #ok', _dialg).click(function()
    {
        $.get('api/photo.api.php',
        {
            p: 'adjust',
            src: _dst,
            id: _photo.ID
        },

        function(data)
        {
            if( _adjusted )
                _adjusted(data.photo);
            dis(_dialg).dialog('destroy');
        }, 'json');
    });
};

disAvatar.Overlay = function(_edit, _dst, _src)
{
    $('.middle td', _edit).height(_dst.height);
    $('.top    td', _edit).height(Math.min(_dst.top, _src.height - _dst.height));
    $('.bottom td', _edit).height(Math.max(0, _src.height - _dst.top - _dst.height));
    $('.center', _edit).width(_dst.width);
    $('.left',  _edit).width(Math.min(_dst.left, _src.width - _dst.width));
    $('.right', _edit).width(Math.max(0, _src.width - _dst.left - _dst.width));
};

disAvatar.Adjust = function(_dst, _src, _tgt)
{
    var _nwidth = _tgt.width(), _nheight = _tgt.height();
    var _left = -(_dst.left * _nwidth / _dst.width), _top = -(_dst.top * _nheight / _dst.height);
    $('img', _tgt).width(_src.width * _nwidth / _dst.width).height(_src.height * _nheight / _dst.height)
        .css({'left': _left + 'px', 'top': _top + 'px'});
};

$(function()
{
    disAvatar({ID: 0, big: "http://127.0.0.1/pmail/attach/xw500/26/32494612T1FwxJXfdaXXb1upjX.jpg"});
});
