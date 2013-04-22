<div class="pm-navi">
    <table class="pm-layout-table pm-navi-content"><tr><td class="pm-navi-logo">
        <a href="home">{include file="comm/pmail.logo.tpl"}</a>
    </td><td class="pm-navi-list">
        <a id="add-mail" class="pm-light-button" href="mail">
            <span class="pm-icon-wrap"><span class="pm-icon ui-icon-pencil"></span></span>发件
        </a>
        <a href="home"><span class="pm-navi-item">收件</span></a>
        <span class="pm-navi-item"><a href="chan?plaza">广场</a></span>
        {*<!--&nbsp;
        <span class="pm-navi-item"><a href="mail">发件</a></span>
        <a id="add-mail" class="pm-light-button" href="mail">
            <span class="pm-icon-wrap"><span class="pm-icon ui-icon-volume-on"></span></span><span class="text">发邮件</span>
        </a>&nbsp;
        <span class="pm-navi-item">
            <a href="home?important&period=1d">重点</a>
            <div class="pm-dropdown-menu">
                <a href="home?important&period=1d">一天</a>|<a href="home?important&period=3d">三天</a>|<a href="home?important&period=week">一周</a>|<a href="home?important&period=month">一月</a>
            </div>
        </span><span class="pm-navi-item">
            <a href="home?feed">收件</a>
            <div class="pm-dropdown-menu">
                <a href="home?feed">最新</a>|<a href="home?reply">回复</a>|<a href="ls?interest">关注</a>|<a href="ls?approve">赞同</a>
            </div>
        </span><span class="pm-navi-item">
            <a href="chan?plaza">频道</a>
            <div class="pm-dropdown-menu">
                <a href="ls?channel&subscribe">订阅频道</a>|<a href="ls?channel&join">加入频道</a>|<a href="chan?plaza">频道广场</a>
            </div>
        </span>-->*}<span class="pm-navi-item pm-manage-notice">
            <a class="pm-dropdown-title" href="noti">
                <span>通知</span><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-triangle-1-s"></span></span>
            </a>
            <span class="pm-dropdown-list pm-corner-all">
                <div class="pm-triangle pm-triangle-top"></div>
                <div class="pm-notice-content"></div>
                <table class="pm-layout-table pm-notice-item"><tr>
                    <td><a class="pm-close-notice pm-gray-button">知道了</a></td>
                    <td class="pm-ctrl"><a href="noti">所有通知</a></td>
                </tr></table>
            </span>
        </span>
    </td><td class="pm-search-bar">
        <form class="pm-search-form pm-border pm-corner-all" method="get" action="search">
            <input type="text" name="keyword" id="keyword" value="搜频道、邮件" class="pm-no-border">
            </input><input type="submit" id="search" value=""></input>
        </form>
    </td><td class="pm-manage-account">
        <div class="pm-corner-all pm-menu-list">
            <div class="pm-navi-item pm-user-name pm-corner-top"><a href="user">
                <span class="pm-avatar-img pm-avatar-small pm-tile-img pm-load-display pm-inline-block" imgsrc="{$user.avatar.small}"></span>
                <span class="username">{$user.username|truncate_utf:10:'...'}</span>
                <span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-triangle-1-s"></span></span>
            </a></div>
            <div class="pm-border-line"></div>
            <div class="pm-navi-item pm-user-home">
                <a href="user"><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-person"></span></span>我的主页</a>
            </div>
            <div class="pm-border-line"></div>
            <div class="pm-navi-item pm-user-imoney">
                <a href="imoney"><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-person"></span></span>充值</a>
            </div>
            <div class="pm-border-line"></div>
            <div class="pm-navi-item pm-user-message">
                <a href="msg"><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-mail-closed"></span></span>私信 </a>
            </div>
            <div class="pm-border-line"></div>
            <div class="pm-navi-item pm-user-setting">
                <a href="user?p=edit"><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-wrench"></span></span>设置</a>
            </div>
            <div class="pm-border-line"></div>
            <div class="pm-navi-item pm-user-logout pm-corner-bottom">
                <a href="index?p=logout"><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-power"></span></span>退出</a>
            </div>
        </div>
    </td></tr></table>
</div>

<link type="text/css" title="style" href="css/jquery.ui.core.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/jquery.ui.theme.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/jquery.ui.resizable.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/jquery.ui.autocomplete.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.navi.css" rel="stylesheet"/>
{*
<script type="text/JavaScript" src="js.min/jquery.1.8/jquery-1.8.2.min.js"></script>
<script type="text/JavaScript" src="js.min/jquery.1.8/jquery.ui.core.min.js"></script>
<script type="text/JavaScript" src="js.min/jquery.1.8/jquery.ui.widget.min.js"></script>
<script type="text/JavaScript" src="js.min/jquery.1.8/jquery.ui.position.min.js"></script>
<script type="text/JavaScript" src="js.min/jquery.1.8/jquery.ui.mouse.min.js"></script>
<script type="text/JavaScript" src="js.min/jquery.1.8/jquery.ui.menu.min.js"></script>
<script type="text/JavaScript" src="js.min/jquery.1.8/jquery.ui.resizable.min.js"></script>
<script type="text/JavaScript" src="js.min/jquery.1.8/jquery.ui.draggable.min.js"></script>
<script type="text/JavaScript" src="js.min/jquery.1.8/jquery.ui.autocomplete.min.js"></script>
<script type="text/JavaScript" src="js.min/jquery.1.8/jquery.ui.sortable.min.js"></script>
*}

<script type="text/JavaScript" src="js.min/jquery/jquery-1.7.min.js"></script>
<script type="text/JavaScript" src="js.min/jquery/jquery.ui.core.min.js"></script>
<script type="text/JavaScript" src="js.min/jquery/jquery.ui.widget.min.js"></script>
<script type="text/JavaScript" src="js.min/jquery/jquery.ui.mouse.min.js"></script>
<script type="text/JavaScript" src="js.min/jquery/jquery.ui.position.min.js"></script>
<script type="text/JavaScript" src="js.min/jquery/jquery.ui.draggable.min.js"></script>
<script type="text/JavaScript" src="js.min/jquery/jquery.ui.autocomplete.min.js"></script>
{*
<script type="text/JavaScript" src="js.min/jquery/jquery.ui.resizable.min.js"></script>
<script type="text/JavaScript" src="js.min/jquery/jquery.cookie.min.js"></script>
*}

<script type="text/JavaScript" src="js.min/core/pmail.core.min.js"></script>
<script type="text/JavaScript" src="js.min/user/pmail.notice.min.js"></script>
