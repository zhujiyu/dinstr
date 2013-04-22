/*!
 * PMAIL项目 JavaScript v2.4.12
 * @bref 修改用户资料
 * @author 朱继玉
 * @copyright @2012 公众邮件网
 */
if( typeof pmVeri == 'undefined' )
    pm('<div>').tip({mess: '需要引用文件pmail.user.veri.js.'});

pmUser = function(_edit)
{
    var _user = this;
    this.edit = _edit;

    $('.pm-ctrl', _edit).show();
    $('.pm-edit-group', _edit).hide();
    $('.pm-edit-info', _edit).show();

    $('.pm-user-gender #gender_' + $('.pm-user-gender', _edit).attr('gender'), _edit).attr('checked', true);
    $('.pm-msg-setting #msg_' + $('.pm-msg-setting', _edit).attr('set'), _edit).attr('checked', true);

    this.veri = new pmVeri(_edit);
    $('textarea', _edit).autoResize();
//    $('.pm-edit-desc textarea').autoResize();
//    $('.pm-edit-extend #contact').autoResize();
    
//    pm('.pm-tag', _edit).close(
//    {
//        onClose: pmUser.removeTag
//    });
//    $('.pm-tag-add', _edit).click(function()
//    {
//        var _edit = $(this).parents('.pm-edit-item');
//        var _tag = $('#tags', _edit).val();
//        var _tag_list = $('.pm-tag-list .pm-tag', _edit);
//
//        for (var i = 0; i < _tag_list.length; i ++ )
//        {
//            if( $(_tag_list[i]).text() == _tag )
//            {
//                pm('<div>').tip({mess: '你已经添加了标签“' + _tag + '”'});
//                return false;
//            }
//        }
//
//        $.get('api/pmail.api.user.php',
//        {
//            p: 'edit',
//            view: 'tag-add',
//            id: _edit.parents('.pm-user-edit').attr('id'),
//            tag: _tag
//        },
//
//        function(data)
//        {
//            if( data.msg )
//            {
//                pm('<div>').tip({mess: data.msg}); return;
//            }
//
//            var _new_tag = $('<span class="pm-tag"></span>').attr('id', data.tag_id)
//                .text(_tag).appendTo($('.pm-tag-list', _edit));
//            pm(_new_tag).close(
//            {
//                onClose: pmUser.removeTag
//            });
//        }, 'json');
//        return false;
//    });

    $('.pm-upload-file-button', _edit).click(function()
    {
        var _logo = $('.pm-edit-logo', _edit);
        pm.upload($('.pm-upload-photo-ctrl', _edit),
        {
            avatar : function(_photo)
            {
                $('#' + _photo.ID + ' img', _logo).attr(_photo.big);
                $('.pm-avatar-img', _logo).attr('imgsrc', _photo.big);
                $('.pm-avatar-img', _logo).attr('id', _photo.ID);
                $('input#logo', _logo).val(_photo.ID);
                pm('.pm-avatar-img', _logo).loadImg();
            }
        });
        return false;
    });

    _edit.submit(function()
    {
        if( $('.pm-page-navi .current').hasClass('pword') )
            _user.updatePword();
        else
            _user.saveInfo();
        return false;
    });

    $('.save', _edit).click(function()
    {
        _edit.submit();
    });

    return this;
};

pmUser.fn = pmUser.prototype = new pmBase();

pmUser.fn.extend(
{
    updatePword : function()
    {
        var _edit = this.edit;
        var _veri = this.veri;
        var _word = $('input:text#pword', _edit).val();
//        var _pwd2 = $('input:text#pword', _edit).val();

        if( !_veri.emailChecked() || !_veri.pwordChecked() || !_veri.pword2Checked()
                || !_veri.unameChecked() )
            return false;

        $.get('api/pmail.api.user.php',
        {
            p: 'edit',
            view: 'pword',
            id: _edit.attr('id'),
            old_pword: $('#oldpword', _edit).val(),
            new_pword: _word
        },

        function(data)
        {
            if( data.msg )
            {
                pm('<div>').tip({mess: data.msg}); return;
            }
            pm('<div>').tip({mess: '密码更新成功！'});
        }, 'json');
        return false;
    },

    saveInfo: function()
    {
        var _edit = this.edit;

        $.get('api/pmail.api.user.php',
        {
            p: 'edit', view: 'info', id: _edit.attr('id'),
            avatar: $('.pm-avatar-img', _edit).attr('id'),
            uname: $('#uname', _edit).val(),
            sign: $('#sign', _edit).val(),
            self_intro: $('#desc', _edit).val(),
            live_city: $('#live_city', _edit).val(),
            contact: $('#contact', _edit).val(),
//            gender: $('.pm-user-gender input:checked', _edit).val(),
            msg_setting: $('.pm-msg-setting input:checked', _edit).val()
//            atme_setting: $('.pm-atme-setting input:checked', _edit).val()
        },

        function(data)
        {
            if( data.msg )
            {
                pm('<div>').tip({mess: data.msg}); return;
            }
            
            pm('<div>').tip({mess: '保存成功！'});
        }, 'json');
    }
});

//pmUser.removeTag = function()
//{
//    var _tag = $(this).parents('.pm-tag');
//
//    $.get('api/pmail.api.user.php',
//    {
//        p: 'edit',
//        view: 'tag-remove',
//        tag_id: _tag.attr('id')
//    },
//
//    function(data)
//    {
//        if( data.msg )
//        {
//            pm('<div>').tip({mess: data.msg}); return;
//        }
//        _tag.remove();
//    }, 'json');
//};

$(function()
{
    var _edit = $('.pm-user-edit');

    $('.pm-page-navi .item').click(function()
    {
        $('.pm-edit-group', _edit).hide();
        
        if( $(this).hasClass('info') )
        {
            $('.pm-edit-info', _edit).show();
            $('.pm-ctrl', _edit).show();
            pm('.pm-avatar-img', _edit).loadImg();
        }
        else if( $(this).hasClass('extend') )
        {
            $('.pm-edit-extend', _edit).show();
            $('.pm-ctrl', _edit).show();
        }
        else if( $(this).hasClass('tags') )
        {
            $('.pm-edit-tags', _edit).show();
            $('.pm-ctrl', _edit).hide();
        }
        else if ( $(this).hasClass('priv') )
        {
            $('.pm-edit-priv', _edit).show();
            $('.pm-ctrl', _edit).show();
        }
        else if( $(this).hasClass('deny') )
        {
            $('.pm-edit-deny', _edit).show();
            $('.pm-ctrl', _edit).hide();
        }
        else if( $(this).hasClass('pword') )
        {
            $('.pm-edit-pword', _edit).show();
            $('.pm-ctrl', _edit).show();
        }

        $('.pm-page-navi .current').removeClass('current');
        $(this).addClass('current');
    });
    
    var _user = new pmUser(_edit);
});
