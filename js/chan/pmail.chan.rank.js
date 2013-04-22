pm.plus = function()
{
    var _chan = $(this).parents('[weight][rank]');
    var _list = _chan.parents('.pm-channel-list');
    var _wght = _chan.attr('weight');
    var _chn0 = $('.pm-channel[weight=' + (parseInt(_wght) + 1) + ']:first', _list);

    $.get('api/pmail.api.channel.php',
    {
        p: 'weight',
        item: 'plus',
        id: _chan.attr('id')
    },

    function(data)
    {
        if( data.msg )
        {
            pm('<div>').tip({content: data.msg});
            return;
        }
        pm(_chan).prompt();

        var _sub = data.subscribe;
        _chan.attr('weight', _sub.weight);
        _chan.attr('rank', _sub.rank);
        $('.pm-weight', _chan).text(_sub.weight);

        if( _chn0.length < 1 )
        {
            if( $('.pm-channel[weight=' + _wght + ']', _list).length < 1 )
                return;
            _chn0 = $('.pm-channel[weight=' + _wght + ']:first', _list);
        }

        if( _chan.prev().length < 1 )
            return;
        if( _chn0.prev().attr('id') === _chan.attr('id') )
            return;

        _chn0.before(_chan);
        pm(_list).chanBorder();
    }, 'json');
    pm(_list).chanBorder();
};

pm.minus = function()
{
    var _chan = $(this).parents('[weight][rank]');
    var _list = _chan.parents('.pm-channel-list');
    var _wght = _chan.attr('weight');
    var _chn0 = $('.pm-channel[weight=' + _wght + ']:last', _list);

    $.get('api/pmail.api.channel.php',
    {
        p: 'weight',
        item: 'minus',
        id: _chan.attr('id')
    },

    function(data)
    {
        if( data.msg )
        {
            pm('<div>').tip({content: data.msg});
            return;
        }
        pm(_chan).prompt();

        var _sub = data.subscribe;
        _chan.attr('weight', _sub.weight);
        _chan.attr('rank', _sub.rank);
        $('.pm-weight', _chan).text(_sub.weight);

        if( _chn0.attr('id') === _chan.attr('id') )
            return;
        _chn0.after(_chan);
        pm(_list).chanBorder();
    }, 'json');
};

pm.startSort = function(event, ui)
{
    ui.item.addClass('pm-sorting');
    $('.pm-content-border', ui.item).remove();
    $('.pm-channel-list .pm-channel-shirt').height(ui.item.height());
//    $('.pm-channel-list .pm-channel-shirt').css({height: ui.item.height() + 'px'});
};

pm.stopSort = function(event, ui)
{
    var _chan = ui.item.removeClass('pm-sorting');
    var _list = _chan.parents('.pm-channel-list');
    var _wght = _chan.attr('weight'), _rank = _chan.attr('rank');
    var _prev = ui.item.prev(), _next = ui.item.next();

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

    $.get('api/pmail.api.channel.php',
    {
        p: 'weight',
        item: 'rank',
        id: _chan.attr('id'),
        weight: _wght,
        rank: _rank
    },

    function(data)
    {
        if( data.msg )
        {
            pm('<div>').tip({content: data.msg});
            return;
        }
        pm(_chan).prompt();

        _chan.attr('weight', data.subscribe.weight);
        _chan.attr('rank', data.subscribe.rank);
        $('.pm-weight', _chan).text(data.subscribe.weight);

        $('.pm-channel[weight=' + _wght + ']', _list).each(function()
        {
            if( $(this).attr('rank') >= _rank)
                $(this).attr('rank', parseInt($(this).attr('rank')) + 1);
        });
    }, 'json');
    pm(_list).chanBorder();
};

pm.fn.extend(
{
    chanList : function()
    {
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
            $('.pm-weight', _wght).text($(this).attr('weight'));
        });

        $('.pm-weight-plus', this[0]).click(pm.plus);
        $('.pm-weight-minus', this[0]).click(pm.minus);
        this.chanBorder();

        if( typeof $.ui !== 'undefined' && typeof $.ui.sortable !== 'undefined' )
        {
            $(this[0]).disableSelection().sortable(
            {
                start: pm.startSort,
                stop: pm.stopSort,
                placeholder: "pm-channel-shirt"
            });
        }
    },

    chanBorder : function()
    {
        $('.pm-channel .pm-content-border', this[0]).remove();
        $('.pm-channel', this[0]).append('<div class="pm-content-border"></div>');
        $('.pm-channel:last .pm-content-border', this[0]).remove();
    }
});

$(function()
{
    pm('.pm-channel-list').chanList();
    var _wght = $('.pm-page-right .pm-weight-manage');
    $('.pm-weight-plus', _wght).click(pm.plus);
    $('.pm-weight-minus', _wght).click(pm.minus);
});
