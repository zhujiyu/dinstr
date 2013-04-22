/*!
 * PMAIL项目 JavaScript v2.4.12
 * @bref 用户信息验证
 * @author 朱继玉
 * @copyright @2012 公众邮件网
 */
pmVeri = function(_edit)
{
    $('.pm-desc', _edit).hide();
    $('.pm-veri', _edit).hide();

    this.edit = _edit;
    this.lasted = {uname: '', email: '', pword: ''};
    this.uname = new Array();
    this.pword = '';
    this.email = new Array();

    var _veri = this;
    this.lasted.uname = $('input:text#uname', _edit).val();
    if( this.lasted.uname !== undefined && this.lasted.uname !== '' )
        this.uname.push(this.lasted.uname);
    $('input:text#uname', _edit).focus(pmVeri.focus).blur(function()
    {
        var _name = $(this).val();
        var _data = $(this).parents('.pm-veri-data');

        if( _veri.checkUname(_name) )
        {
            $('.pm-veri', _data).show();
            return ;
        }
        else if( _veri.lasted.uname === _name )
        {
            $('.pm-desc', _data).show();
            return ;
        }
        _veri.lasted.uname = _name;

        $.get("api/pmail.api.user.php", 
        {
            p: 'veri',
            item: 'uname',
            uname: _name
        },

        function(data)
        {
            if( data.msg != null && data.msg != '' )
            {
                $('.pm-desc', _data).text(data.msg).show();
            }
            else
            {
                $('.pm-veri', _data).show();
                _veri.uname.push(_name);
            }
        }, 'json');
    });

    this.lasted.email = $('input:text#email', _edit).val();
    if( this.lasted.email !== undefined && this.lasted.email !== '' )
        this.email.push(this.lasted.email);
    $('input:text#email', _edit).focus(pmVeri.focus).blur(function()
    {
        var _mail = $(this).val();
        var _data = $(this).parents('.pm-veri-data');

        if( _veri.checkEmail(_mail) )
        {
            $('.pm-veri', _data).show();
            return ;
        }
        else if( _veri.lasted.email === _mail )
        {
            $('.pm-desc', _data).show();
            return ;
        }
        _veri.lasted.email = _mail;

        $.get("api/pmail.api.user.php",
        {
            p: 'veri',
            item: 'email',
            email: _mail
        },

        function(data)
        {
            if( data.msg != null && data.msg != '' )
            {
                $('.pm-desc', _data).text(data.msg).show();
            }
            else
            {
                $('.pm-veri', _data).show();
                _veri.email.push(_mail);
            }
        }, 'json');
    });

    this.lasted.pword = $('input:password#pword', _edit).val();
    if( this.lasted.email !== undefined && this.lasted.email !== '' )
        this.pword = this.lasted.pword;
    $('input:password#pword', _edit).focus(pmVeri.focus).blur(function()
    {
        var _word = $(this).val();
        var _data = $(this).parents('.pm-veri-data');

        if( _word == null || _word == '' )
        {
            $('.pm-desc', _data).text('至少6位的英文数字特殊字符').show();
            return ;
        }
        else if( _veri.pword === _word )
        {
            $('.pm-veri', _data).show();
            return ;
        }
        else if( _veri.lasted.pword === _word )
        {
            $('.pm-desc', _data).show();
            return ;
        }
        _veri.lasted.pword = _word;

        if( _word.match(/^\w{6,}$/) )
        {
            _veri.pword = _word;
            $('.pm-veri', _data).show();
        }
        else
        {
            $('.pm-desc', _data).text('至少6位的英文数字特殊字符').show();
        }

        _data = $('input:password#pword2', _edit).parents('.pm-veri-data');
        if( $('input:password#pword2', _edit).val()
            && $(this).val() !== $('input:password#pword2', _edit).val() )
        {
            $('.pm-desc', _data).text('两次输入的密码不同').show();
        }
        else if( $('input:password#pword2', _edit).val() !== undefined
            && $('input:password#pword2', _edit).val() !== '' )
        {
            $('.pm-veri', _data).show();
        }
    });

    $('input:password#pword2', _edit).focus(pmVeri.focus).blur(function()
    {
        var _data = $(this).parents('.pm-veri-data');

        if( $('input:password#pword', _edit).val()
            && $(this).val() !== $('input:password#pword', _edit).val() )
        {
            $('.pm-desc', _data).text('两次输入的密码不同').show();
        }
        else if( $(this).val() !== undefined && $(this).val() !== '' )
        {
            $('.pm-veri', _data).show();
        }
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
    checkEmail: function(_mail)
    {
        if( _mail === undefined || _mail === '' )
            return false;
        for ( var i = 0; i < this.email.length; i ++ )
        {
            if( this.email[i] === _mail )
                return true;
        }
        return false;
    },

    checkUname: function(_name)
    {
        if( _name === undefined || _name === '' )
            return false;
        for ( var i = 0; i < this.uname.length; i ++ )
        {
            if( this.uname[i] === _name )
                return true;
        }
        return false;
    },

    pwordChecked: function()
    {
        var _pword_veri = $('.pm-veri-data.pword', this.edit);
        if( this.pword !== $('input:password#pword', this.edit).val() )
        {
            pm('<div>').tip({mess: '请输入密码！'});
            pm(_pword_veri).prompt();
            pm('.pm-desc', _pword_veri).show();
            return false;
        }
        return true;
    },

    pword2Checked: function()
    {
        var _pword2_veri = $('.pm-veri-data.pword2', this.edit);
        if( $('input:password#pword2', this.edit).val() !== this.pword )
        {
            pm('<div>').tip({mess: '请确认密码！'});
            pm(_pword2_veri).prompt();
            pm('.pm-desc', _pword2_veri).show();
            return false;
        }
        return true;
    },

    unameChecked: function()
    {
        var _uname_veri = $('.pm-veri-data.uname', this.edit);
        if( !this.checkUname($('input:text#uname', this.edit).val()) )
        {
            pm('<div>').tip({mess: '请输入用户昵称！'});
            pm(_uname_veri).prompt();
            pm('.pm-desc', _uname_veri).show();
            return false;
        }
        return true;
    },

    emailChecked: function()
    {
        var _email_veri = $('.pm-veri-data.email', this.edit);
        if( !this.checkEmail($('input:text#email', this.edit).val()) )
        {
            pm('<div>').tip({mess: '请输入邮箱地址！'});
            pm(_email_veri).prompt();
            pm('.pm-desc', _email_veri).show();
            return false;
        }
        return true;
    }
});
