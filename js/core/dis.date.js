/**
 * JavaScript的DateFormat类
 * [File  ] dis.date.js
 * [Author] cerdar
 * [Date  ] 2006-06-20
 * copy right cerdar
 * 
 * @param pattern String 时间戳.
 * @param formatSymbols String 日期格式.
 ***/
function DateFormat(pattern, formatSymbols)
{
    if( typeof pattern === 'undefined' || pattern === null )
    {
        pattern = "yyyy-MM-dd HH:mm:ss SSS";
    }

    if( typeof formatSymbols === 'undefined' || formatSymbols === null )
    {
        formatSymbols = "yMdHmsS";
    }

    this.pattern = pattern;
    this.formatSymbols = formatSymbols;
};

// 返回当前时间
function getTime(date)
{
    if( date === null )
    {
        date = new Date();
    }
    
    var y = date.getFullYear();
    var M = date.getMonth() + 1;
    var d = date.getDate();
    var h = date.getHours();
    var m = date.getMinutes();
    var s = date.getSeconds();
    var S = date.getTime()%1000;
    var html = y + "-";

    if(M < 10)
    {
        html += "0";
    }
    html += M + "-";

    if(d < 10)
    {
        html += "0";
    }
    html += d + " ";

    if(h < 10)
    {
        html += "0";
    }
    html += h + ":";

    if(m < 10)
    {
        html += "0";
    }
    html += m + ":";

    if(s < 10)
    {
        html += "0";
    }
    html += s;
    
    html += " ";

    if(S < 100)
    {
        html += "0";
    }

    if(S < 10)
    {
        html += "0";
    }

    html += S;

    return html;
}

DateFormat.prototype.format = function(date)
{
    var
        time = getTime(date),
        
    // 标记存入数组
        cs = this.formatSymbols.split(""),

    // 格式存入数组
        fs = this.pattern.split(""),

    // 构造数组
        ds = time.split(""),

    // 标志年月日的结束下标
        y = 3, M = 6, d = 9,
        H = 12, m = 15, s = 18, S = 22;

    // 逐位替换年月日时分秒和毫秒
    for(var i = fs.length - 1; i > -1; i--)
    {
        switch (fs[i])
        {
            case cs[0]:
            {
                fs[i] = ds[y--];
                break;
            }
            case cs[1]:
            {
                fs[i] = ds[M--];
                break;
            }
            case cs[2]:
            {
                fs[i] = ds[d--];
                break;
            }
            case cs[3]:
            {
                fs[i] = ds[H--];
                break;
            }
            case cs[4]:
            {
                fs[i] = ds[m--];
                break;
            }
            case cs[5]:
            {
                fs[i] = ds[s--];
                break;
            }
            case cs[6]:
            {
                fs[i] = ds[S--];
                break;
            }
        }
    }

    return fs.join("");
};

/**格式化日期
 *@param date - String
 *@return - Date
 */
DateFormat.prototype.parse = function(date)
{
    var y = "";
    var M = "";
    var d = "";
    var H = "";
    var m = "";
    var s = "";
    var S = "";

    // 标记存入数组
    var cs = this.formatSymbols.split("");

    // 格式存入数组
    var ds = this.pattern.split("");

    // date   = "2005-08-22 12:12:12 888";
    // format = "yyyy-MM-dd HH:mm:ss SSS";
    // sign   = "yMdHmsS";
    var size = Math.min(ds.length, date.length);

    for(var i = 0; i < size; i ++ )
    {
        switch (ds[i])
        {
            case cs[0]:
            {
                y += date.charAt(i);
                break;
            }
            case cs[1]:
            {
                M += date.charAt(i);
                break;
            }
            case cs[2]:
            {
                d += date.charAt(i);
                break;
            }
            case cs[3]:
            {
                H += date.charAt(i);
                break;
            }
            case cs[4]:
            {
                m += date.charAt(i);
                break;
            }
            case cs[5]:
            {
                s += date.charAt(i);
                break;
            }
            case cs[6]:
            {
                S += date.charAt(i);
                break;
            }
        }
    }
    
    if(y.length < 1) y = 0; else y = parseInt(y);
    if(M.length < 1) M = 0; else M = parseInt(M);
    if(d.length < 1) d = 0; else d = parseInt(d);
    if(H.length < 1) H = 0; else H = parseInt(H);
    if(m.length < 1) m = 0; else m = parseInt(m);
    if(s.length < 1) s = 0; else s = parseInt(s);
    if(S.length < 1) S = 0; else S = parseInt(S);

    var d = new Date(y, M - 1, d, H, m, s, S);

    return d;
};

disTime = function(time)
{
    var _now = disTime.now(), _today = disTime.today();
    
    $(time).each(function()
    {
        var _time = $(this).attr('time');
        
        if( Math.floor(_now - _time) < 3600 )
        {
            disTime.list.push($(this));
        }
        
        $(this).text(disTime.format(_time, _now, _today));
    });
};

disTime.format = function(_time, _now)
{
    var _tm = Math.floor(_now - _time);
    var _date = new Date(_time * 1000);
    var _fmtstr;
    
    if( _tm < 60 )
        _fmtstr = _tm + '\u79d2\u524d';
    else if( _tm < 3600 )
        _fmtstr = Math.floor(_tm / 60) + '\u5206\u949f\u524d';
    else if( dis.today < _time )
        _fmtstr = '\u4eca\u5929 ' + new DateFormat("HH:mm").format(_date);
    else if( dis.thisYear < _time )
        _fmtstr = new DateFormat("MM-dd HH:mm").format(_date);
    else
        _fmtstr = new DateFormat("yyyy-MM-dd HH:mm").format(_date);
    
    return _fmtstr;
};

disTime.now = function()
{
    var myDate = new Date();
    return myDate.getTime() / 1000;
};

disTime.today = function()
{
    var myDate = new Date();
    var today = new Date(myDate.getFullYear(), myDate.getMonth(), myDate.getDate());
    return today.getTime() / 1000;
};

disTime.thisYear = function()
{
    var myDate = new Date();
    var thisYear = new Date(myDate.getFullYear(), 0, 1);
    return thisYear.getTime() / 1000;
};

disTime.list = [];

disTime.run = function()
{
    var _now = disTime.now();
    
    for( var i = disTime.list.length - 1; i >= 0; i -- )
    {
        var _note = $(disTime.list[i]), _time = _note.attr('time');
        _note.text(disTime.format(_time, _now));
        if( Math.floor(_now - _time) >= 3600 )
            disTime.list.splice(i, 1);
    }
    
    setTimeout(disTime.run, 1000);
};

_dis.fn.extend(
{
    time: function()
    {
        disTime(this[0]);
    }
});

$(function()
{
    dis.today = disTime.today();
    dis.thisYear = disTime.thisYear();
    dis('.dis-date').time();
    setTimeout(disTime.run, 1000);
});
