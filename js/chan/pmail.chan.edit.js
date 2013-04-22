/*
 * 模块五：编辑和创建Channel
 */
pmVeri = function(edit)
{
    $('.pm-desc', edit).hide();
    $('.pm-veri', edit).hide();

    this.edit = edit;
    this.name = new Array();
    this.domain = new Array();
    this.lasted =
    {
        name: $('input:text#name', edit).val(),
        domain: $('input:text#domain', edit).val()
    };
    
    if( this.lasted.name !== undefined && this.lasted.name !== '' )
        this.name.push(this.lasted.name);
    if( this.lasted.domain !== undefined && this.lasted.domain !== '' )
        this.domain.push(this.lasted.domain);

    var _veri = this;
    $('input:text#name', edit).focus(pmVeri.focus).blur(function()
    {
        var _name = $(this).val(),
            _data = $(this).parents('.pm-veri-data');

        if( _veri.checkName() )
        {
            $('.pm-veri', _data).show();
            return ;
        }
        else if( _veri.lasted.name === _name )
        {
            $('.pm-desc', _data).text('请输入频道名称').show();
            return ;
        }
        _veri.lasted.name = _name;
        
        $.get("api/pmail.api.channel.php",
        {
            p: 'veri',
            view: 'name',
            name: _name
        },

        function(data)
        {
            if( data.msg && data.msg !== '' )
            {
                $('.pm-desc', _data).text(data.msg).show();
            }
            else
            {
                $('.pm-veri', _data).show();
                _veri.name.push(_name);
            }
        }, 'json');
    });

    $('input:text#domain', edit).focus(pmVeri.focus).blur(function()
    {
        var _main = $(this).val(),
            _data = $(this).parents('.pm-veri-data');

        if( _veri.checkDomain() )
        {
            $('.pm-veri', _data).show();
            return ;
        }
        else if( _veri.lasted.domain === _main )
        {
            $('.pm-desc', _data).text('请输入频道名称').show();
            return ;
        }
        _veri.lasted.domain = _main;

        $.get("api/pmail.api.channel.php",
        {
            p: 'veri',
            view: 'domain',
            name: _main
        },

        function(data)
        {
            if( data.msg && data.msg !== '' )
            {
                $('.pm-desc', _data).text(data.msg).show();
            }
            else
            {
                $('.pm-veri', _data).show();
                _veri.domain.push(_main);
            }
        }, 'json');
    });

    return this;
};

pmVeri.focus = function()
{
    var _veri = $(this).parents('.pm-veri-data');
    $('.pm-desc', _veri).hide();
    $('.pm-veri', _veri).hide();
};

pmVeri.fn = pmVeri.prototype = new pmBase();

pmVeri.fn.extend(
{
    edit: 0,
    checkName: function(_name)
    {
        if( _name === undefined || _name === '' )
            return false;
        for ( var i = 0; i < this.name.length; i ++ )
        {
            if( this.name[i] === _name )
                return true;
        }
        return false;
    },

    checkDomain: function(_domain)
    {
        if( _domain === undefined || _domain === '' )
            return false;
        for ( var i = 0; i < this.domain.length; i ++ )
        {
            if( this.domain[i] === _domain )
                return true;
        }
        return false;
    },

    nameChecked: function()
    {
        var _name = $('input:text#name', this.edit).val();
        for ( var i = 0; i < this.name.length; i ++ )
        {
            if( this.name[i] === _name )
                return true;
        }
        return false;
    },

    domainChecked: function()
    {
        var _domain = $('input:text#domain', this.edit).val();
        for ( var i = 0; i < this.domain.length; i ++ )
        {
            if( this.domain[i] === _domain )
                return true;
        }
        return false;
    }
});

_pMail.Veri = pmVeri;

pmTag = function(_id, _tag, _list)
{
    var _node = $('<span class="pm-tag"></span>').attr('id', _id).text(_tag).appendTo(_list);
    pm(_node).close(
    {
        onClose: pmTag.remove
    });
    return _node;
};

pmTag.remove = function()
{
    var _tag = $(this).parents('.pm-tag');
    var _chl = $(this).parents('.pm-channel-edit');

    $.get('api/pmail.api.channel.php',
    {
        p: 'edit', view: 'tag-remove',
        id: _chl.attr('id'),
        tag_id: _tag.attr('id')
    },

    function(data)
    {
        if( data.msg )
        {
            pm('<div>').tip({content: data.msg}); return;
        }
        _tag.remove();
    }, 'json');
};

pmTag.add = function()
{
    var _chl  = $(this).parents('.pm-channel-edit');
    var _tag  = $('#tags', _chl).val();
    var _tags = $('.pm-tag-list .pm-tag', _chl);

    for (var i = 0; i < _tags.length; i ++ )
    {
        if( $(_tags[i]).text() === _tag )
        {
            pm('<div>').tip({mess: '该频道已经添加了标签“' + _tag + '”'}); return false;
        }
    }

    $.get('api/pmail.api.channel.php',
    {
        p: 'edit',
        view: 'tag-add',
        id: _chl.attr('id'),
        tag: _tag
    },

    function(data)
    {
        if( data.msg )
        {
            pm('<div>').tip({content: data.msg}); return;
        }
        pmTag(data.tag_id, _tag, $('.pm-tag-list', _chl));
    }, 'json');
    return false;
};

pmEdit = function(_edit)
{
    var _veri = new pmVeri(_edit);
    
    $('.pm-edit-group', _edit).hide();
    $('.pm-edit-info', _edit).show();
    $('.pm-edit-desc textarea', _edit).autoResize();

    $('.pm-upload-file-button', _edit).click(function()
    {
        var _edit = $(this).parents('.pm-edit-logo');
        pm.upload($('.pm-upload-photo-ctrl', _edit),
        {
            avatar : function(_photo)
            {
                $('#' + _photo.ID + ' img', _edit).attr(_photo.big);
                $('.pm-avatar-img', _edit).attr('imgsrc', _photo.big);
                $('.pm-avatar-img', _edit).attr('id', _photo.ID);
                $('input#logo', _edit).val(_photo.ID);
                pm('.pm-avatar-img', _edit).loadImg();
            }
        });
        return false;
    });

    $('.pm-save', _edit).click(function()
    {
        if( !_veri.nameChecked() )
        {
            pm('<div>').tip({mess: '请输入正确的频道名称！'}); return;
        }
        _edit.submit();
    });

    _edit.submit(function()
    {
        $.get('api/pmail.api.channel.php',
        {
            p: 'edit', view: 'save',
            id: $(this).attr('id'),
            //domain: $('#domain', $(this)).val(),
            name: $('#name', $(this)).val(), 
            logo: $('#logo', $(this)).val(),
            desc: $('#desc', $(this)).val()
        },

        function(data)
        {
            if( data.msg )
            {
                pm('<div>').tip({content: data.msg}); return;
            }
            pm('<div>').tip({mess: '保存成功！'});
        }, 'json');
        return false;
    });
    
    $('.pm-tag-add', _edit).click(pmTag.add);
    pm('.pm-tag', _edit).close({ onClose: pmTag.remove });
};

pmCreate = function(_create)
{
    if( _create === undefined || _create.length === 0 )
        return this;

    var _veri = new pmVeri(_create);
    $('.pm-channel-create textarea').autoResize();

    $('.pm-upload-file-button', _create).click(function()
    {
        var _edit = $(this).parents('.pm-edit-logo');
        pm.upload($('.pm-upload-photo-ctrl', _edit),
        {
            avatar : function(_photo)
            {
                $('#' + _photo.ID + ' img', _edit).attr(_photo.big);
                $('.pm-avatar-img', _edit).attr('imgsrc', _photo.big);
                $('.pm-avatar-img', _edit).attr('id', _photo.ID);
                $('input#logo', _edit).val(_photo.ID);
                pm('.pm-avatar-img', _edit).loadImg();
            }
        });
        return false;
    });

    $('.pm-save', _create).click(function()
    {
        if( !_veri.nameChecked($('input:text#name', _create).val()) )
        {
            pm('<div>').tip({mess: '请输入正确的频道名称！'});
            return ;
        }
        _create.submit();
    });
    return this;
};

$(function()
{
    var _edit = $('.pm-channel-edit');

    $('.pm-page-navi .item').click(function()
    {
        $('.pm-page-navi .current').removeClass('current');
        $(this).addClass('current');

        $('.pm-edit-group', _edit).hide();
        $('.pm-ctrl', _edit).show();

        if( $(this).hasClass('setting') )
        {
            $('.pm-edit-info', _edit).show();
        }
        else if( $(this).hasClass('logo') )
        {
            $('.pm-edit-logo', _edit).show();
            pm('.pm-channel-logo', _edit).loadImg();
        }
        else if( $(this).hasClass('tags') )
        {
            $('.pm-edit-tags', _edit).show();
            $('.pm-ctrl', _edit).hide();
        }

        return false;
    });

    pmEdit(_edit);
    pmCreate($('.pm-channel-create'));
});
