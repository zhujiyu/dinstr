
/**
 * JavaScript的DateFormat类
 * [File  ] pmail.date.js
 * [Author] cerdar
 * [Date  ] 2006-06-20
 * copy right cerdar
/**----------------------------------------------------------------*/
function DateFormat(pattern, formatSymbols)
{
//    if(pattern == null || pattern == undefined)
    if( typeof pattern === 'undefined' || pattern === null )
    {
        pattern = "yyyy-MM-dd HH:mm:ss SSS";
    }

//    if(formatSymbols == null || formatSymbols == undefined)
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

/**
 *@param datetime - String
 *
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

    for(var i=0; i < size; i++)
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

pmTime = function(time)
{
    var _now = pmTime.now(), _today = pmTime.today();
    
    $(time).each(function()
    {
        var _time = $(this).attr('time');
        if( Math.floor(_now - _time) < 3600 )
        {
            pmTime.list.push($(this));
        }
        $(this).text(pmTime.format(_time, _now, _today));
    });
};

pmTime.now = function()
{
    var myDate = new Date();
    return myDate.getTime() / 1000;
};

pmTime.today = function()
{
    var myDate = new Date();
    var today = new Date(myDate.getFullYear(), myDate.getMonth(), myDate.getDate());
    return today.getTime() / 1000;
};

pmTime.thisYear = function()
{
    var myDate = new Date();
    var thisYear = new Date(myDate.getFullYear(), 0, 1);
    return thisYear.getTime() / 1000;
};

pmTime.format = function(_time, _now)
{
    var _tm = Math.floor(_now - _time);
    var _date = new Date(_time * 1000);
    
    if( _tm < 60 )
        return _tm + '\u79d2\u524d';
    else if( _tm < 3600 )
        return Math.floor(_tm / 60) + '\u5206\u949f\u524d';
    else if( pm.today < _time )
        return '\u4eca\u5929 ' + new DateFormat("HH:mm").format(_date);
    else if( pm.thisYear < _time )
        return new DateFormat("MM-dd HH:mm").format(_date);
    
    return new DateFormat("yyyy-MM-dd HH:mm").format(_date);
    
//    else if( pm.today < _time )
//    {
//        var df1 = new DateFormat("HH:mm");
//        return '\u4eca\u5929 ' + df1.format(_date);
//        return '\u4eca\u5929 ' + _date.toLocaleTimeString();
//    }
//    else if( pm.thisYear < _time )
//    {
//        var df1 = new DateFormat("MM-dd HH:mm");
//        return '\u4eca\u5929 ' + df1.format(_date);
//        return '\u4eca\u5929 ' + _date.toLocaleTimeString();
//    }
//    
//    var df1 = new DateFormat("yyyy-MM-dd HH:mm");
//    return df1.format(_date);
//    return (_date.getMonth() + 1) + '-' + _date.getDate() + ' ' + _date.getHours() + ':' + _date.getMinutes();
};

pmTime.list = [];

pmTime.run = function()
{
    var _now = pmTime.now();
    
    for( var i = pmTime.list.length - 1; i >= 0; i -- )
    {
        var _note = $(pmTime.list[i]), _time = _note.attr('time');
        _note.text(pmTime.format(_time, _now));
        if( Math.floor(_now - _time) >= 3600 )
            pmTime.list.splice(i, 1);
    }
    
    setTimeout(pmTime.run, 1000);
};

_pMail.today = pmTime.today();
_pMail.thisYear = pmTime.thisYear();

_pMail.fn.extend(
{
    time: function()
    {
        pmTime(this[0]);
    }
});

$(function()
{
    pm('.pm-date').time();
    setTimeout(pmTime.run, 1000);
});

//function setValue(_id, _value)
//{
//    var obj = document.getElementById(_id);
//
//    if(obj != null)
//    {
//        obj.value = _value;   
//    }
//}
//
//function test0()
//{
//    // 使用自定义符号
//    var df = new DateFormat(" HH:mm:ss SSS");
//
//    setValue("ID_TXT0", df.format(new Date()));
//}
//
//function test1()
//{
//    // 标准日期格式
//    var df1 = new DateFormat("yyyy-MM-dd HH:mm:ss SSS");
//   
//    var df2 = new DateFormat("MM/dd/yy");
//    var df3 = new DateFormat("dd/MM/yy");
//    var df4 = new DateFormat("yyyy年MM月dd日 HH时mm分ss秒 SSS毫秒");
//
//    // 解析出Date对象
//    var dt = df1.parse("2004-02-23 13:24:48 789");
//   
//    setValue("ID_TXT1", "[" + df1.format(dt) + "] [" + df2.format(dt) + "] [" + df3.format(dt) + "] [" + df4.format(dt) + "]");
//}
//
//function test2()
//{
//    // 使用自定义符号
//    var df = new DateFormat("AAAA-BB-CC DD:EE:FF GGG", "ABCDEFG");
//    var dt = df.parse("2004-02-23 13:24:48 789");
//
//    setValue("ID_TXT2", df.format(dt));
//}
//
//function test3()
//{
//    // 一般弹出日历对话框, 得到日期, 此处假设为当前日期
//    var retval = new DateFormat("yyyy-MM-dd").format(new Date());
//
//    if(retval != null )
//    {
//        var obj = document.getElementById("ID_TXT3");
//
//        if(obj != null)
//        {
//            // 时间日期格式化对象
//            var dtf = new DateFormat(obj.format);
//           
//            // 给日期追加当前时间
//            retval += new DateFormat(" HH:mm:ss SSS").format(new Date());
//
//            // 按照给定的格式解析出时间日期字符串
//            obj.value = "[" + retval + "] : " + dtf.format(new DateFormat("yyyy-MM-dd HH:mm:ss SSS").parse(retval));
//        }
//    }
//}
//
//function test4()
//{
//    var df = new DateFormat("yyyy-MM-dd HH:mm:ss SSS");
//    var dt = df.parse("2007-02-02 13:54:24 250");
//
//    setValue("ID_TXT4", new DateFormat("yyyy年MM月dd日 HH时mm分ss秒 SSS毫秒").format(dt));
//}
//
//function dispatch(src)
//{
//    if(src == null || src == undefined)
//    {
//        return;
//    }
//
//    if(src.value == null || src.value == undefined)
//    {
//        return;
//    }
//
//    var fun = window[src.value];
//
//    if(fun == null || fun == undefined)
//    {
//        return;
//    }
//
//    if("function" != typeof(fun))
//    {
//        return;
//    }
//
//    fun();
//}
