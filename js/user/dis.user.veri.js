/*!
 * DINSTR项目 JavaScript v2.4.12
 * @bref 用户信息验证
 * @author 朱继玉<zhuhz82@126.com>
 * @copyright @2013 海报栏
 */
disVeri = function(_edit)
{
    $('.dis-desc', _edit).hide();
    $('.dis-veri', _edit).hide();

    this.edit = _edit;
//    this.lasted = {uname: '', email: '', pword: ''};
    this.uname = new Array();
    this.pword = '';
    this.email = new Array();

    var _veri = this, _lasted = {uname: '', email: '', pword: ''};
    
    _lasted.email = $('input:text#email', _edit).val();
    if( _lasted.email !== undefined && _lasted.email !== '' )
        this.email.push(_lasted.email);
    
    _lasted.pword = $('input:password#pword', _edit).val();
    if( _lasted.email !== undefined && _lasted.email !== '' )
        this.pword = _lasted.pword;
    
    _lasted.uname = $('input:text#uname', _edit).val();
    if( _lasted.uname !== undefined && _lasted.uname !== '' )
        this.uname.push(_lasted.uname);
//    _lasted = _lasted;
    
    $('input:text#email', _edit).focus(disVeri.focus).blur(function()
    {
        var _mail = $(this).val();
        var _data = $(this).parents('.dis-veri-data');

//        if( _mail === "" )
//            return;
        if( _veri.checkEmail(_mail) )
        {
            $('.dis-veri', _data).show();
            return ;
        }
        else if( _lasted.email === _mail )
        {
            $('.dis-desc', _data).show();
            return ;
        }
        _lasted.email = _mail;

        $.get("api/user.api.php",
        {
            p: 'veri',
            item: 'email',
            email: _mail
        },

        function(data)
        {
            if( data.msg !== null && data.msg !== '' )
            {
                $('.dis-desc', _data).text(data.msg).show();
//                dis('<div>').dialog({content: data.msg});
            }
            else
            {
                $('.dis-veri', _data).show();
                _veri.email.push(_mail);
            }
        }, 'json');
    });

    $('input:password#pword', _edit).focus(disVeri.focus).blur(function()
    {
        var _word = $(this).val();
        var _data = $(this).parents('.dis-veri-data');

        if( _word === null || _word === '' )
        {
            $('.dis-desc', _data).text('至少6位的英文数字特殊字符').show();
            return ;
        }
        else if( _veri.pword === _word )
        {
            $('.dis-veri', _data).show();
            return ;
        }
        else if( _lasted.pword === _word )
        {
            $('.dis-desc', _data).show();
            return ;
        }
        _lasted.pword = _word;

        if( _word.match(/^\w{6,}$/) )
        {
            _veri.pword = _word;
            $('.dis-veri', _data).show();
        }
        else
        {
            $('.dis-desc', _data).text('至少6位的英文数字特殊字符').show();
        }

        _data = $('input:password#pword2', _edit).parents('.dis-veri-data');
        if( $('input:password#pword2', _edit).val()
            && $(this).val() !== $('input:password#pword2', _edit).val() )
        {
            $('.dis-desc', _data).text('两次输入的密码不同').show();
        }
        else if( $('input:password#pword2', _edit).val() !== undefined
            && $('input:password#pword2', _edit).val() !== '' )
        {
            $('.dis-veri', _data).show();
        }
    });

    $('input:password#pword2', _edit).focus(disVeri.focus).blur(function()
    {
        var _data = $(this).parents('.dis-veri-data');

        if( $('input:password#pword', _edit).val()
            && $(this).val() !== $('input:password#pword', _edit).val() )
        {
            $('.dis-desc', _data).text('两次输入的密码不同').show();
        }
        else if( $(this).val() !== undefined && $(this).val() !== '' )
        {
            $('.dis-veri', _data).show();
        }
    });
    
    $('input:text#uname', _edit).focus(disVeri.focus).blur(function()
    {
        var _name = $(this).val();
        var _data = $(this).parents('.dis-veri-data');

        if( _veri.checkUname(_name) )
        {
            $('.dis-veri', _data).show();
            return ;
        }
        else if( _lasted.uname === _name )
        {
            $('.dis-desc', _data).show();
            return ;
        }
        _lasted.uname = _name;

        $.get("api/user.api.php",
        {
            p: 'veri',
            item: 'uname',
            uname: _name
        },

        function(data)
        {
            if( data.msg !== null && data.msg !== '' )
            {
                $('.dis-desc', _data).text(data.msg).show();
//                dis('<div>').dialog({content: data.msg});
            }
            else
            {
                $('.dis-veri', _data).show();
                _veri.uname.push(_name);
            }
        }, 'json');
    });

    return this;
};

disVeri.focus = function()
{
    var _veri = $(this).parents('.dis-veri-data');
    $('.dis-desc', _veri).hide();
    $('.dis-veri', _veri).hide();
};

disVeri.fn = disVeri.prototype = new disBase();

disVeri.fn.extend(
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
        var _pword_veri = $('.dis-veri-data.pword', this.edit);
        if( this.pword !== $('input:password#pword', this.edit).val() )
        {
            pm('<div>').tip({mess: '请输入密码！'});
            pm(_pword_veri).prompt();
            pm('.dis-desc', _pword_veri).show();
            return false;
        }
        return true;
    },

    pword2Checked: function()
    {
        var _pword2_veri = $('.dis-veri-data.pword2', this.edit);
        if( $('input:password#pword2', this.edit).val() !== this.pword )
        {
            pm('<div>').tip({mess: '请确认密码！'});
            pm(_pword2_veri).prompt();
            pm('.dis-desc', _pword2_veri).show();
            return false;
        }
        return true;
    },

    unameChecked: function()
    {
        var _uname_veri = $('.dis-veri-data.uname', this.edit);
        if( !this.checkUname($('input:text#uname', this.edit).val()) )
        {
            pm('<div>').tip({mess: '请输入用户昵称！'});
            pm(_uname_veri).prompt();
            pm('.dis-desc', _uname_veri).show();
            return false;
        }
        return true;
    },

    emailChecked: function()
    {
        var _email_veri = $('.dis-veri-data.email', this.edit);
        if( !this.checkEmail($('input:text#email', this.edit).val()) )
        {
            pm('<div>').tip({mess: '请输入邮箱地址！'});
            pm(_email_veri).prompt();
            pm('.dis-desc', _email_veri).show();
            return false;
        }
        return true;
    }
});
