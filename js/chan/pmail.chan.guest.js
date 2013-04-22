/*!
 * PMAIL项目 JavaScript v2.4.12
 *
 * 该文件依赖 pmail.core.js jquery.cookie.js
 *
 * @bref 频道相关操作
 * @author 朱继玉
 * @copyright @2012 公众邮件网
 */
pmChanCookie = function()
{
    var _cookie = $.cookie('guest-subscribes');
    if( _cookie !== null )
    {
        _cookie = $.parseJSON(_cookie);
        this.ids = _cookie.ids;
        this.weights = _cookie.weights;
        this.ranks = _cookie.ranks;
    }
    else
    {
        this.ids = new Array();
        this.weights = new Array();
        this.ranks = new Array();
    }
    return this;
};

pmChanCookie.fn = pmChanCookie.prototype = new pmBase();

pmChanCookie.fn.extend(
{
    index : function(id)
    {
        return this.ids.indexOf(parseInt(id));
    },

    add : function(id)
    {
        var i = 0;
        var _ids = this.ids, _weights = this.weights, _ranks = this.ranks;

        this.ids = new Array();
        this.weights = new Array();
        this.ranks = new Array();

        for( ; i < _ids.length; i ++ )
        {
            if( _weights[i] === 1 )
            {
                this.ids.push(parseInt(id));
                this.weights.push(1);
                this.ranks.push(_ranks[i] + 1);
                break;
            }

            this.ids.push(_ids[i]);
            this.weights.push(_weights[i]);
            this.ranks.push(_ranks[i]);
        }

        for( ; i < _ids.length; i ++ )
        {
            this.ids.push(_ids[i]);
            this.weights.push(_weights[i]);
            this.ranks.push(_ranks[i]);
        }

        return this.save();
    },

    remove : function(id)
    {
        var _idx = this.index(id);

        if( _idx >= 0 )
        {
            this.ids.splice(_idx, 1);
            this.weights.splice(_idx, 1);
            this.ranks.splice(_idx, 1);
        }

        return this.save();
    },

    maxRank : function(weight)
    {
        for( var i = 0; i < this.ids.length; i ++ )
        {
            if( this.weights[i] === weight )
                return this.ranks[i];
        }
        return 0;
    },

    rank : function(id, _weight, _rank)
    {
        var i = 0;
        var _ids = this.ids, _weights = this.weights, _ranks = this.ranks;

        this.ids = new Array();
        this.weights = new Array();
        this.ranks = new Array();

        id = parseInt(id);
        _weight = parseInt(_weight);
        _rank = parseInt(_rank);

        for( ; i < _ids.length && _weights[i] > _weight; i ++ )
        {
            if( _ids[i] === id )
                continue;
            this.ids.push(_ids[i]);
            this.weights.push(_weights[i]);
            this.ranks.push(_ranks[i]);
        }

        for( ; i < _ids.length && _weights[i] === _weight && _ranks[i] >= _rank; i ++ )
        {
            if( _ids[i] === id )
                continue;
            this.ids.push(_ids[i]);
            this.weights.push(_weights[i]);
            this.ranks.push(_ranks[i] + 1);
        }

        this.ids.push(id);
        this.weights.push(_weight);
        this.ranks.push(_rank);

        for( ; i < _ids.length; i ++ )
        {
            if( _ids[i] === id )
                continue;
            this.ids.push(_ids[i]);
            this.weights.push(_weights[i]);
            this.ranks.push(_ranks[i]);
        }

        return this.save();
    },

    plus : function(id)
    {
        var _idx = this.index(id);
        if( _idx < 0 )
        {
            this.add(id);
            _idx = this.index(id);
        }

        var _weight = this.weights[_idx] + 1;
        var _rank = this.maxRank(_weight) + 1;
        return this.rank(id, _weight, _rank);
    },

    minus : function(id)
    {
        var _idx = this.index(id);
        if( _idx < 0 )
            return this.add(id);
        if( this.weights[_idx] <= 0 )
            return this;

        var _weight = this.weights[_idx] - 1;
        var _rank = this.maxRank(_weight) + 1;
        return this.rank(id, _weight, _rank);
    },

    // 重新设置Cookie
    reset : function(_ids, _weights, _ranks)
    {
        if( typeof _ids !== 'object' || typeof _weights !== 'object' || typeof _ranks !== 'object' )
        {
            throw new Error('格式不对');
        }

        this.ids = new Array();
        this.weights = new Array();
        this.ranks = new Array();

        for( var i = 0; i < _ids.length; i ++ )
        {
            this.add(_ids[i]);

            if( i >= _weights.length )
                continue;

            if( i < _ranks.length )
            {
                this.rank(_ids[i], _weights[i], _ranks[i]);
            }
            else
            {
                this.rank(_ids[i], _weights[i]);
            }
        }

        return this.save();
    },

    get : function(id)
    {
        var _idx = this.index(id);
        var status = {ID: 0, channel_id: id, user_id: 0, weight: 0, rank: 0, role: -1};

        if( _idx >= 0 )
        {
            status.role = 0;
            status.weight = this.weights[_idx];
            status.rank = this.ranks[_idx];
        }

        return status;
    },

    save : function()
    {
        var _cookie = {ids : this.ids, weights : this.weights, ranks : this.ranks};
        $.cookie('guest-subscribes', $.json_encode(_cookie), {expires: 60});
        return this;
    }
});

pmCookie = function(value)
{
    if( arguments.length > 0 && (value === null || typeof value !== "object") )
    {
        $.cookie('guest-subscribes', null, {expires: 60});
    }

    if( arguments.length > 0 && typeof value === "object" && value !== null )
    {
        var _cookie = $.extend({ids: [], weights: [], ranks: []}, value);
        return (new pmChanCookie()).reset(_cookie.ids, _cookie.weights, _cookie.ranks);
    }

    return new pmChanCookie();
};

pm.plus = function()
{
    var _cookie = pmCookie();
    var _chan = $(this).parents('.pm-channel');
    var _list = _chan.parents('.pm-channel-list');
    var _wght = _chan.attr('weight');
    var _chn0 = $('.pm-channel[weight=' + (parseInt(_wght) + 1) + ']:first', _list);
    var _stts = _cookie.plus(_chan.attr('id')).get(_chan.attr('id'));

    _chan.attr('weight', _stts.weight);
    _chan.attr('rank', _stts.rank);
    $('.pm-weight', _chan).text(_stts.weight);
    pm(_chan).prompt();

    if( _chan.prev().length < 1 )
        return;
    if( _chn0.length < 1 )
    {
        if( $('.pm-channel[weight=' + _wght + ']', _list).length < 1 )
            return;
        _chn0 = $('.pm-channel[weight=' + _wght + ']:first', _list);
    }

    var _prev = _chn0.prev();
    if( _prev.length > 0 && _prev.attr('id') === _chan.attr('id') )
        return;
    _chn0.before(_chan);
    pm(_list).chanBorder();
};

pm.minus = function()
{
    var _cookie = pmCookie();
    var _chan = $(this).parents('.pm-channel');
    var _list = _chan.parents('.pm-channel-list');
    var _chn0 = $('.pm-channel[weight=' + _chan.attr('weight') + ']:last', _list);
    var _stts = _cookie.minus(_chan.attr('id')).get(_chan.attr('id'));

    _chan.attr('weight', _stts.weight);
    _chan.attr('rank', _stts.rank);
    $('.pm-weight', _chan).text(_stts.weight);
    pm(_chan).prompt();

    if( _chn0.attr('id') === _chan.attr('id') )
        return;
    _chn0.after(_chan);
    pm(_list).chanBorder();
};

pm.startSort = function(event, ui)
{
    ui.item.addClass('pm-sorting');
    $('.pm-border-line', ui.item).remove();
    $('.pm-channel-list .pm-channel-shirt').height(ui.item.height() - 10);
//    $('.pm-channel-list .pm-channel-shirt').css({height: ui.item.height() + 'px'});
};

pm.stopSort = function(event, ui)
{
    var _chan = ui.item.removeClass('pm-sorting');
    var _wght = _chan.attr('weight'), _rank = _chan.attr('rank');
    var _prev = _chan.prev(), _next = _chan.next();

    if( _next.length > 0 )
    {
        if( _wght < _next.attr('weight') )
            _wght = parseInt(_next.attr('weight'));
        if( _wght === _next.attr('weight') )
            _rank = parseInt(_next.attr('rank')) + 1;
    }

    if( _prev.length > 0 )
    {
        if( _wght > _prev.attr('weight') )
            _wght = parseInt(_prev.attr('weight'));
        if( _wght === _prev.attr('weight') && _rank > _prev.attr('rank') )
            _rank = parseInt(_prev.attr('rank')) - 1;
        _rank = _rank < 1 ? 1 : _rank;
        if( _next.length > 0 && _wght === _next.attr('weight') && _rank <= _prev.attr('rank') )
            _rank = parseInt(_next.attr('rank')) + 1;
    }
    pmCookie().rank(_chan.attr('id'), _wght, _rank);

    _chan.attr('weight', _wght);
    _chan.attr('rank', _rank);
    $('.pm-weight', _chan).text(_wght);
    pm(_chan).prompt();

    var _list = _chan.parents('.pm-channel-list');
    $('.pm-channel[weight=' + _wght + ']', _list).each(function()
    {
        if( $(this).attr('rank') >= _rank)
            $(this).attr('rank', parseInt($(this).attr('rank')) + 1);
    });
    pm(_list).chanBorder();
};

pm.fn.extend(
{
});

pm.chanBtns =
{
    subscribe: '<a class="pm-subscribe pm-light-button"><span class="pm-inline-block"><span class="pm-icon ui-icon-pin-s"></span></span>&nbsp;订阅频道</a>',
    cancelSub: '<span class="pm-gray-button"><span class="pm-inline-block"><span class="pm-icon ui-icon-pin-w"></span></span>&nbsp;已订阅&nbsp;|&nbsp;<a class="pm-subscribe-cancel">取消</a></span>',
    join : '<a class="pm-join-apply pm-light-button"><span class="pm-inline-block"><span class="pm-icon ui-icon-volume-off"></span></span>&nbsp;申请加入</a>',
    cancelJoin: '<span class="pm-gray-button"><span class="pm-inline-block"><span class="pm-icon ui-icon-volume-on"></span></span>&nbsp;已加入&nbsp;|&nbsp;<a class="pm-quit">退出</a></span>'
};

pm.chanCache = [];
pm.check = '<div class="pm-check pm-avatar-small"><img src="css/images/checkx.png"></div>';

pm.subscribe = function()
{
    var _btn = $(this);
    var _chn = $(this).parents('.pm-channel-ctrl');

    if( _btn.hasClass('pm-light-button') )
        _btn.before($(pm.chanBtns.cancelSub)).remove();
    else
        _btn.text('取消订阅').unbind('click').removeClass('pm-subscribe').addClass('pm-subscribe-cancel');

    $('.pm-subscribe-cancel', _chn).click(pm.cancelSubscribe);
    $('.pm-logo-list .pm-channel[id =' + _chn.attr('id') + ']').append(pm.check);

    pm.chanCache[_chn.attr('id')] = 0;
    pmCookie().add(_chn.attr('id'));
};

pm.cancelSubscribe = function()
{
    var _btn = $(this);
    var _chn = $(this).parents('.pm-channel-ctrl');

    if( _btn.parent('.pm-gray-button').length > 0 )
        _btn.parent().before($(pm.chanBtns.subscribe)).remove();
    else
        _btn.text('订阅频道').unbind('click').removeClass('pm-subscribe-cancel').addClass('pm-subscribe');

    $('.pm-subscribe', _chn).click(pm.subscribe);
    $('.pm-logo-list .pm-channel[id =' + _chn.attr('id') + '] .pm-check').remove();

    pm.chanCache[_chn.attr('id')] = 0;
    pmCookie().remove(_chn.attr('id'));
};

pm.apply = function()
{
    pm('<div>').tip({mess: '你尚未登录，请先登录。<br>如果没有账户，请先注册新账户。'});
};

pm.fn.extend(
{
    chanList : function()
    {
        if( typeof $.ui === 'undefined' || typeof $.ui.sortable === 'undefined' )
            return;
        var _cookie = pmCookie();

            alert(400);
        $('.pm-channel', this[0]).each(function()
        {
            var _wght = $('.pm-weight-manage', this);
            if( _wght.length > 0 )
                return;

            _wght = $('<div class="pm-weight-manage pm-inline-block">\
<div class="pm-inline-block pm-weight-ctrl">\
    <div class="pm-weight-plus  ui-corner-all pm-weight-button" title="增加该频道的权值"><span class="pm-arrow pm-arrow-up"><em class="head">&diams;</em></span></div>\
    <div class="pm-shirt-h"></div>\
    <div class="pm-weight-minus ui-corner-all pm-weight-button" title="减少该频道的权值"><span class="pm-arrow pm-arrow-dwon"><em class="head">&diams;</em></span></div>\
</div>\
<div class="pm-weight ui-corner-all pm-inline-block"></div></div>').prependTo($(this));

            var status = _cookie.get($(this).attr('id'));
            $(this).attr('weight', status.weight);
            $(this).attr('rank', status.rank);
            $('.pm-weight', _wght).text(status.weight);
            alert(418);
        });

        $('.pm-weight-plus', this[0]).click(pm.plus);
        $('.pm-weight-minus', this[0]).click(pm.minus);

        $(this[0]).disableSelection().sortable(
        {
            start: pm.startSort,
            stop: pm.stopSort,
            placeholder: "pm-channel-shirt"
        });

        this.chanBorder();
    },

    chanBorder : function()
    {
        $('.pm-channel .pm-border-line', this[0]).remove();
        $('.pm-channel', this[0]).append('<div class="pm-border-line"></div>');
        $('.pm-channel:last .pm-border-line', this[0]).remove();
    },

    channel : function()
    {
        $('.pm-subscribe', this[0]).click(pm.subscribe);
        $('.pm-subscribe-cancel', this[0]).click(pm.cancelSubscribe);
        $('.pm-apply-join', this[0]).click(pm.apply);
    }
});

$(function()
{
    var _cookie = pmCookie();

    $('.pm-logo-list .pm-channel').click(function()
    {
        pm.chanCache[$(this).attr('id')] = 0;
        if( _cookie.index($(this).attr('id')) < 0 )
        {
            _cookie.add($(this).attr('id'));
            $(this).append(pm.check);
        }
        else
        {
            _cookie.remove($(this).attr('id'));
            $('.pm-check', this).remove();
        }
    }).

    each(function()
    {
        if( _cookie.index($(this).attr('id')) < 0 )
            return;
        $(this).append(pm.check);
    });

    var _wght = $('.pm-page-right .pm-weight-manage');
    $('.pm-weight-plus', _wght).click(pm.plus);
    $('.pm-weight-minus', _wght).click(pm.minus);

    pm('.pm-page-right .pm-channel-ctrl').channel();
    pm('.pm-channel').channel();
    pm('.pm-channel-list').chanList();

    pm('.pm-channel-manage').close();
});
