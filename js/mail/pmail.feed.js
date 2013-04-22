/*!
 * PMAIL项目 JavaScript v2.4.12
 *
 * 消息提示
 * @bref 消息提示
 * @author 朱继玉
 * @copyright @2012 公众邮件网
 */
pmFeed = function(_list)
{
    this.list = $(_list);
    this.feeded = [];
    this.tofeed = [];

    this.load();
    return this;
};

pmFeed.fn = pmFeed.prototype = new pmBase();

pmFeed.fn.extend(
{
    feeded: [], tofeed: [],
    loading: 0, step : 20, disped: 20, piece: 1, // 用于截取下一步需要加载的信息IDs
    page: 0, pageSize: 40,

    next_ids : function()
    {
        var _ids  = [];
        var _left = this.disped;
        var _rght = _left + this.step;

        for( var i = _left; i < this.feeded.length && i < _rght; i ++ )
            _ids.push(this.feeded[i]);
        return _ids;
    },

    notice : function(count)
    {
        var _feed = this;
        var _notice = $('.pm-notice-feed', this.list);

        if( _notice.length === 0 )
            _notice = $('<div class="pm-notice-feed"></div>').append('<a>').prependTo(this.list);
        $('a', _notice).text('有' + count + '条新邮件').click(function()
        {
            _notice.remove();
            _feed.read();
            return false;
        });

        return this;
    },

    refrush : function()
    {
        var _feed = this;

        $.get('api/pmail.api.feed.php', 
        {
            p: 'refrush'
        },

        function(data)
        {
            if( data.err )
            {
                pm('<div>').tip({content : data.err});
                return;
            }
            
            _feed.tofeed = data.tofeed;
            if( _feed.tofeed.length > 0 )
                _feed.notice(_feed.tofeed.length);
            else
                $('.pm-notice-feed', _feed.list).remove();

            setTimeout(function()
            {
                _feed.refrush();
            }, 120000);
        }, 'json');

        return this;
    },

    /**
     * 加载第一页数据的ID
     * 当页面向下滚动时，并不是重新加载一页，
     * 只是在将第一页没有完全的显示的后20个ID解析显示出来
     */
    load : function()
    {
        var _feed = this;

        $.get('api/pmail.api.feed.php',
        {
            p: 'list',
            start : _feed.feeded.length,
            count : _feed.pageSize
        },

        function(data)
        {
            if( data.err )
            {
                pm('<div>').tip({content : data.err});
                return;
            }
            
            _feed.feeded = _feed.feeded.concat(data.feeded);
        }, 'json');
    },

    read : function()
    {
        var _feed = this;

        $.get('api/pmail.api.load.php',
        {
            p : 'parse',
            item: 'flow',
            flow_ids : _feed.tofeed
        },

        function(data)
        {
            if( $(data).hasClass('pm-err') )
            {
                pm('<div>').tip({content: data});
                return;
            }

            var _data  = $('<div>' + data + '</div>').prependTo(_feed.list);
            var _mails = $('.pm-mail', _data).after('<div class="pm-content-border"></div>');
            pm(_mails).mail();
        }, 'html');

        $.get('api/pmail.api.feed.php', 
        {
            p: 'read'
        },

        function(data)
        {
            if( data.err)
            {
                pm('<div>').tip({content : data.err});
                return;
            }
            
            _feed.disped += _feed.tofeed.length;
            _feed.feeded = _feed.tofeed.concat(_feed.feeded);
            _feed.tofeed = [];
        }, 'json');

        return this;
    },

    append_flows : function()
    {
        var _feed = this;
        var _ids  = this.next_ids();

        if( this.loading === 1 || _ids.length === 0 )
            return this;
        this.loading= 1;
        pm(this.list).loading();

        $.get('api/pmail.api.load.php',
        {
            p : 'parse',
            item : 'flow',
            flow_ids : _ids
        },

        function(data)
        {
            if( $(data).hasClass('pm-err') )
            {
                pm('<div>').tip({content: data});
                return;
            }

            var _data = $('<div>' + data + '</div>').appendTo(_feed.list);
            var _objs = $('.pm-mail', _data).before('<div class="pm-content-border"></div>');
            if( _objs.length === 0 )
                return;

            pm(_objs).mail().theme();
            pm('.pm-channel-link', _objs).link();
            pm(_feed.list).loaded();
            
            var _first = $('.pm-mail:first', _feed.list);
            if( _first.prev().length > 0 )
            {
                _first.prev().remove();
                window.scroll(0, 0);
            }
            
            _feed.loading = 0;
            _feed.disped += _feed.step;
            _feed.piece ++;
            if( _feed.piece * _feed.step === _feed.pageSize )
                _feed.next_page();
        }, 'html');

        return this;
    },

    next_page : function()
    {
        if( this.page > 0 )
            this.list.append($('<div class="pm-page-manage"><a class="pm-light-button pm-prev-page">上一页</a>&nbsp;<span class="pm-gray-button">' + (this.page + 1) + '</span>&nbsp;<a class="pm-light-button pm-next-page">下一页</a></div>'));
        else
            this.list.append($('<div class="pm-page-manage"><a class="pm-light-button pm-next-page">下一页</a></div>'));
        this.loading = 1;

        var _feed = this;
        $('.pm-next-page', this.list).click(function()
        {
            _feed.load ();
            _feed.page ++;
            _feed.piece = 0;
            
            _feed.loading = 0;
            _feed.list.empty();
            _feed.append_flows();
        });
        
        $('.pm-prev-page', this.list).click(function()
        {
            _feed.disped -= _feed.pageSize * 2;
            _feed.page --;
            _feed.piece = 0;
            
            _feed.loading = 0;
            _feed.list.empty();
            _feed.append_flows();
        });
        
        return this;
    }
});

$(function()
{
    var _feed = new pmFeed('.pm-feed-list');

    setTimeout(function()
    {
        _feed.refrush();
    }, 100);
    
    $(window).scroll(function()
    {
        if( $(window).scrollTop() + document.documentElement.clientHeight >= document.body.clientHeight )
            _feed.append_flows();
    });
});
