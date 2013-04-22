/*!
 * PMAIL项目 JavaScript v2.4.12
 *
 * @bref 信息操作的基础类
 * @author 朱继玉
 * @copyright @2012 公众邮件网
 */
pmReply = function(_mail)
{
    this.mail = $(_mail);
    this.id = _mail.attr('id');
    this.list = $(_mail).next();
    this.parent = $(_mail).parents('.pm-reply-list');
    return this;
};

pmReply.fn = pmReply.prototype = new pmBase;

pmReply.fn.extend(
{
    init : function()
    {
        this.list = $('<div class="pm-reply-list pm-add-list pm-special ui-corner-all"><div class="pm-base-mails"></div><div class="pm-edit"></div><div class="pm-children-list"></div></div>');
        this.list.prepend('<div class="pm-triangle pm-triangle-top"></div>');
        $('.pm-base-mails, .pm-children-list', this.list).empty().hide();
        this.mail.after(this.list);

        pm(this.list).close().prompt();
        this.load();
        var _reply = this;

        pm('.pm-edit', this.list).edit(
        {
            check : function(_edit, _pedit)
            {
                var _data = _pedit.data;
                if( _data.content === _pedit.prompt.content || _data.content === '' )
                {
                    pm('<div>').tip({mess: "请输入回复内容，如果只是转发，请点击转发。"});
                    return false;
                }
                return true;
            },

            custom : function(_edit)
            {
                $('#parent', _edit).val(_reply.id);
                $('.pm-theme-title', _edit).remove();
                $('.pm-mail-target', _edit).before($('<a class="pm-util-tip">你还可以将该回复发送到频道</a>')).hide();

                $("#cancel", _reply.list).click(function()
                {
                    _reply.list.remove();
                });
                $('.pm-util-tip', _edit).click(function()
                {
                    $(this).remove();
                    $('.pm-mail-target', _edit).show();
                });
            },

            success : function(data, _edit, _pedit)
            {
                if( $(data).hasClass('pm-err') )
                {
                    pm('<div>').tip({content: data});
                    return;
                }
                _pedit.complete();

                var _data = $(data);
                var _mlist = _edit.parents('.pm-mail-list');
                var _pmail = $('.pm-mail[id=' + $('.pm-mail-edit #parent', _mlist).val() + ']', _mlist);

                $('.pm-mail-bottom .reply span', _pmail).text(parseInt($('.pm-mail-bottom .reply span', _pmail).text()) + 1);
                if( _pedit.data.channel_id > 0 )
                {
                    var _mails = _data.clone();
                    _edit.parents('.pm-mail-list').prepend(_mails).show();
                    _mails.after('<div class="pm-content-border"></div>');
                    pm(_mails).mail().prompt();
                }

                $('.pm-theme-title', _data).remove();
                $('.pm-mail-avatar .pm-avatar-img', _data).removeClass('pm-avatar-middle').addClass('pm-avatar-small');
                $('.pm-children-list', _edit.parents('.pm-reply-list')).show().prepend(_data);
                _data.before('<div class="pm-border-line"></div>');
                pm(_data).mail().prompt();
            }
        });
    },

    load : function()
    {
        var _reply = this.list;
        $('.pm-children-list', this.list).empty();

        $.post('api/pmail.api.mail.php',
        {
            p : 'load',
            item: 'children',
            mail_id: this.id
        },

        function(data)
        {
            if( $(data).hasClass('pm-err') )
            {
                pm('<div>').tip({content: data}); return;
            }
            var _data = $('<div></div>').append($(data));

            if( $('.pm-mail', _data).length > 0 )
            {
                $('.pm-theme-title', _data).remove();
                $('.pm-mail-avatar .pm-avatar-img', _data).removeClass('pm-avatar-middle').addClass('pm-avatar-small');
                $('.pm-children-list', _reply).append(_data).show();
                $('.pm-mail', _data).before('<div class="pm-border-line"></div>');
                pm('.pm-mail', _data).mail();
            }
            else
                $('.pm-children-list', _reply).hide();
        }, 'html');
    },

    derivative : function()
    {
        var _list = this.mail.parents('.pm-mail-list:first');

        if( this.mail.parent().hasClass('pm-base-mails') )
        {
            this.remove(this.mail.next());
            pm(this.parent).prompt();
        }
        else if( _list.children('.pm-mail[id='+ this.id + ']').length > 0 )
        {
            var _mail = _list.children('.pm-mail[id='+ this.id + ']').after(this.parent);
            this.list = this.parent;
            this.parent = 0;
            window.scroll(0, $(_mail).offset().top);
            pm(_mail).prompt();
        }
        else
        {
            this.mail.prev().remove();
            $('.pm-base-mails', this.parent).append(this.mail).append('<div class="pm-border-line"></div>').show();
            this.list = this.parent;
            this.parent = 0;
        }

        $('.pm-base-mails, .pm-children-list', this.list).empty().hide();
        $('.pm-mail-edit #parent', this.parent).val(this.id);
        this.load();
        pm(this.list).prompt();
    },

    remove : function(_mail)
    {
        var _next = _mail.next();
        if( _next.length > 0 )
            this.remove(_next);
        if( _next.hasClass('pm-mail') || _next.hasClass('pm-border-line') )
            _next.remove();
    }
});

pmManage =
{
    parents : function()
    {
        var _mail = $(this).parents('.pm-mail');
        if( _mail.next().hasClass('pm-parent-list') )
        {
            _mail.next().remove();
            return false;
        }

        $.post('api/pmail.api.mail.php',
        {
            p : 'load',
            item : 'parent',
            mail_id : _mail.attr('id')
        },

        function(data)
        {
            if( $(data).hasClass('pm-err') )
            {
                pm('<div>').tip({content: data});
                return;
            }

            var _list = $('<div class="pm-parent-list pm-special pm-add-list">'+ data + '</div>');
            _mail.after(_list);
            $('.pm-mail', _list).before('<div class="pm-border-line"></div>');
            $('.pm-border-line:first', _list).remove();
            $('.pm-mail-avatar .pm-avatar-img', _list).removeClass('pm-avatar-middle').addClass('pm-avatar-small');
            $('.pm-theme-title', _list).remove();

            pm('.pm-mail', _list).mail();
            pm(_list).prompt().close();
            pm('.pm-load-display', _list).loadImg();
        }, 'html');

        return false;
    },

    collect : function()
    {
        var _bttn = $(this);

        $.post('api/pmail.api.mail.php',
        {
            p : 'manage',
            item : 'collect',
            mail_id : $(this).parents('.pm-mail:first').attr('id')
        },

        function(data)
        {
            if( $(data).hasClass('pm-err') )
            {
                pm('<div>').tip({content: data});
                return;
            }

            _bttn.removeClass('collect').addClass('collected').unbind('click').click(pmManage.cancelCollect);
        }, 'html');
        return false;
    },

    cancelCollect : function()
    {
        var _btn = $(this);
        var _mail = $(this).parents('.pm-mail:first');

        $.post('api/pmail.api.mail.php',
        {
            p : 'mange',
            item : 'cancel-collect',
            mail_id : _mail.attr('id')
        },

        function(data)
        {
            if( $(data).hasClass('pm-err') )
            {
                pm('<div>').tip({content: data});
                return;
            }

            _btn.removeClass('collected').addClass('collect').unbind('click').click(pmManage.collect);
        }, 'html');
        return false;
    },

    reply : function()
    {
        var _reply = new pmReply($(this).parents('.pm-mail:first'));
        if( _reply.parent.length > 0 )
            _reply.derivative();
        else if( _reply.list.hasClass('pm-reply-list') )
            _reply.list.remove();
        else
            _reply.init();
        return false;
    },

    remove : function()
    {
        var _mail = $(this).parents('.pm-mail');
        var _dlog = $('<div class="pm-mail-delete">\
<div class="pm-dialog-content">您确认要删除此条信息吗？</div>\
<div class="pm-dialog-foot pm-ctrl">\
    <a id="cancel" class="pm-gray-button">取消</a><a id="ok" class="pm-light-button">确定</a>\
</div></div>');

        pm(_dlog).dialog({modal: true});
        $('#ok', _dlog).click(function()
        {
            pmManage.removeSubmit(_mail);
        });
    },

    removeSubmit : function(_mail)
    {
        $.post('api/pmail.api.mail.php',
        {
            p : 'manage',
            item : 'delete',
            flow_id : _mail.attr('flow')
        },

        function(data)
        {
            var _data = $(data);
            if( _data.hasClass('pm-err') )
            {
                pm('<div>').tip({content: data}); return;
            }

            _mail.next().remove();
            _mail.fadeOut('slow', function()
            {
                var _list = _mail.parents('.pm-mail-list');
                if( $('.pm-mail', _list).length === 1 )
                    _list.remove();
                else
                    _mail.remove();
                pm.loadImg();
            });
        }, 'html');
    }
};

pmRange = function(_options)
{
    this.options = $.extend(
    {
        navi : $('.pm-range-navi'),
        list : $('.pm-mail-list')
    }, _options);

    this.follow = [];
    $('.item', this.options.navi).removeAttr('href');
    return this;
};

pmRange.fn = pmRange.prototype = new pmBase();

pmRange.fn.extend(
{
    loadIds : function()
    {
        var range = this;

        $.get('api/pmail.api.user.php',
        {
            p: 'list'
        },

        function(data)
        {
            if( data.msg )
            {
                pm('<div>').tip({content: data.msg}); return;
            }

            range.follow = data.user_ids;
            range.filter();
        }, 'json');

        return this;
    },

    filter : function()
    {
        var _list = this.options.list, _ids = this.follow;

        if( !$.isArray(_ids) || _ids.length < 1 )
            return this;

        $('.pm-mail', _list).each(function()
        {
            var _id = $(this).attr('user');
            if( _ids.indexOf(_id) > -1 )
            {
                $(this).show();
                if( $(this).next().hasClass('pm-content-border') )
                    $(this).next().show();
            }
        });
        return this;
    }
});

pmList = function(_list, _options)
{
    this.list = $(_list);
    this.options = $.extend(
    {
        p : 'more',
        item : 'important',
        count : 20,
        object : '.pm-mail',
        more : function(){},
        extname : '',
        extvalue : ''
    }, _options);

    this.loading = 0;
    this.page = 1;
    this.len = $(this.options.object, _list).length;
    return this;
};

pmList.fn = pmList.prototype = new pmBase();

pmList.fn.extend(
{
    loading : 0, page : 1, len : 0,

    load: function()
    {
        var _list = this;
        if( this.loading === 1 )
            return;
        this.loading = 1;

        $.get('api/pmail.api.load.php',
        {
            p: _list.options.p,
            item: _list.options.item,
            page: _list.page,
            count : _list.options.count,
            extname : _list.options.extname,
            extvalue : _list.options.extvalue
        },

        function(data)
        {
            pm(_list.list).loaded();

            if( $(data).hasClass('pm-err') )
            {
                pm('<div>').tip({content: data});
                return;
            }

            var _data = $('<div>' + data + '</div>'),
                _objs = $(_list.options.object, _data).appendTo(_list.list);
            if( _objs.length === 0 )
                return;

            if( _objs.length === _list.options.count )
            {
                _list.loading = 0;
                _list.page ++;
            }
            _list.len += _objs.length;

            if( _list.options.more !== undefined && _list.options.more !== null )
                _list.options.more(_objs);
            pm('.pm-load-display', _objs).loadImg();
        }, 'html');

        pm(this.list).loading();
    }
});

_pMail.list = new Array();

$(function()
{
    var range = new pmRange(),
        _navi = range.options.navi,
        _list = range.options.list;

    $('.all', _navi).click(function()
    {
        $('.current', _navi).removeClass('current');
        $(this).addClass('current');
        $('.pm-mail', _list).show();
        _list.children('.pm-content-border').show();
        return false;
    });

    $('.follow', _navi).click(function()
    {
        $('.current', _navi).removeClass('current');
        $(this).addClass('current');
        $('.pm-mail', _list).hide();
        _list.children('.pm-content-border').hide();

        if( range.follow.length < 1 )
            range.loadIds();
        else
            range.filter();
        return false;
    });

    $('.pm-mail-list').each(function()
    {
        if( !$(this).hasClass('pm-more-list') )
            return;

        var _list = new pmList($(this),
        {
            more : function(_mails)
            {
                _mails.before('<div class="pm-content-border"></div>');
                pm(_mails).mail().theme();
            },

            count : 20,
            p : $('#userid').val() ? 'more' : 'guest-more',
            item : $(this).attr('item'),
            object : '.pm-mail',
            extname : $(this).attr('extname'),
            extvalue : $(this).attr('extvalue')
        });
        pm.list.push(_list);
    });

    $(window).scroll(function()
    {
        if( $(window).scrollTop() + document.documentElement.clientHeight < document.body.clientHeight )
            return;
        for( var i = 0; i < pm.list.length; i ++ )
            pm.list[i].load();
    });
});

//                    _mails.prependTo(_mlist).after('<div class="pm-content-border"></div>');
//                    _mlist.show();

//        var _mail = $(this).parents('.pm-mail:first');
//                _data.prependTo(_clist).before('<div class="pm-border-line"></div>');

//            $('.pm-mail-avatar .pm-channel-logo', _list).remove();
//            $('.pm-mail-avatar .pm-user-avatar', _list).removeClass('pm-user-avatar');

//        pm('.pm-edit', this.list).edit(function(_edit, _pmedit)
//        {
////            _reply.edit(_edit, _pmedit);
//            if( _pmedit.content === _pmedit.prompt.content || _pmedit.content === '' )
//            {
//                pm('<div>').tip({mess: "请输入回复内容，如果只是转发，请点击转发。"});
//                return ;
//            }
//
//            $.post('api/pmail.api.mail.php',
//            {
//                p: 'reply',
//                parent: _pmedit.parent,
//                channel_id: _pmedit.chan_id,
//                weight: _pmedit.weight,
//                content: _pmedit.content,
//                goods: _pmedit.goods,
//                photos: _pmedit.photos
//            },
//
//            function(data)
//            {
//                if( $(data).hasClass('pm-err') )
//                {
//                    pm('<div>').tip({content: data});
//                    return;
//                }
//                _pmedit.complete();
//
//                var _data = $(data);
//                var _mlist = _edit.parents('.pm-mail-list');
//                var _pmail = $('.pm-mail[id=' + $('.pm-mail-edit #parent', _mlist).val() + ']', _mlist);
//                var _clist = $('.pm-children-list', _edit.parents('.pm-reply-list')).show();
//
//                $('.pm-mail-bottom .reply span', _pmail).text(parseInt($('.pm-mail-bottom .reply span', _pmail).text()) + 1);
//                if( _pmedit.chan_id > 0 )
//                {
//                    var _reply = _data.clone();
//                    _reply.prependTo(_mlist).after('<div class="pm-content-border"></div>');
//                    pm(_reply).mail().prompt();
//                    _mlist.show();
//                }
//
//                $('.pm-theme-title', _data).remove();
//                $('.pm-mail-avatar .pm-avatar-img', _data).removeClass('pm-avatar-middle').addClass('pm-avatar-small');
//                _data.prependTo(_clist).before('<div class="pm-border-line"></div>');
//                pm(_data).mail().prompt();
//            }, 'html');
//        },
//
//        function(_edit)
//        {
//            $('#parent', _edit).val(_reply.id);
//            $('.pm-theme-title', _edit).remove();
//            $('.pm-mail-target', _edit).before($('<a class="pm-util-tip">你还可以将该回复发送到频道</a>')).hide();
//
//            $("#cancel", _reply.list).click(function()
//            {
//                _reply.list.remove();
//            });
//            $('.pm-util-tip', _edit).click(function()
//            {
//                $(this).remove();
//                $('.pm-mail-target', _edit).show();
//            });
//        });

//    pretreat : function(_edit)
//    {
//        $('#parent', _edit).val(this.id);
//        alert(59);
//        $('.pm-theme-title', _edit).remove();
//        $('.pm-mail-target', _edit).before($('<a class="pm-mail-tip">你还可以将该回复发送到频道</a>'));
//
//        var _reply = this;
//        $("#cancel", this.list).click(function()
//        {
//            _reply.list.remove();
//        });
//        $('.pm-mail-tip', _edit).click(function()
//        {
//            $(this).remove();
//            $('.pm-mail-target', _edit).show();
//        });
//    },

//    edit : function(_edit, _pmedit)
//    {
//        if( _pmedit.content === _pmedit.prompt.content || _pmedit.content === '' )
//        {
//            pm('<div>').tip({mess: "请输入回复内容，如果只是转发，请点击转发。"});
//            return ;
//        }
//
//        $.post('api/pmail.api.mail.php',
//        {
//            p: 'reply',
//            parent: _pmedit.parent,
//            channel_id: _pmedit.chan_id,
//            weight: _pmedit.weight,
//            content: _pmedit.content,
//            goods: _pmedit.goods,
//            photos: _pmedit.photos
//        },
//
//        function(data)
//        {
//            if( $(data).hasClass('pm-err') )
//            {
//                pm('<div>').tip({content: data});
//                return;
//            }
//            _pmedit.complete();
//
//            var _data = $(data);
//            var _mlist = _edit.parents('.pm-mail-list');
//            var _pmail = $('.pm-mail[id=' + $('.pm-mail-edit #parent', _mlist).val() + ']', _mlist);
//            var _clist = $('.pm-children-list', _edit.parents('.pm-reply-list')).show();
//
//            $('.pm-mail-bottom .reply span', _pmail).text(parseInt($('.pm-mail-bottom .reply span', _pmail).text()) + 1);
//            if( _pmedit.chan_id > 0 )
//            {
//                var _reply = _data.clone();
//                _reply.prependTo(_mlist).after('<div class="pm-content-border"></div>');
//                pm(_reply).mail().prompt();
//                _mlist.show();
//            }
//
//            $('.pm-theme-title', _data).remove();
//            $('.pm-mail-avatar .pm-avatar-img', _data).removeClass('pm-avatar-middle').addClass('pm-avatar-small');
//            _data.prependTo(_clist).before('<div class="pm-border-line"></div>');
//            pm(_data).mail().prompt();
//        }, 'html');
//    },

//_pMail.fn.extend(
//{
//    list: function(_options)
//    {
//        if( $(this[0]).length > 0 )
//            pm.list.push(new pmList(this[0], _options));
//        return this;
//    }
//});
