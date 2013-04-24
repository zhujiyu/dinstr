<div class="pm-navi">
    <table class="pm-layout-table pm-navi-content"><tr><td class="pm-navi-logo">
        <a href="guest">{include file="comm/logo.comm.tpl"}</a>
    </td><td class="pm-navi-list">
        <a id="add-mail" class="pm-light-button pm-opacity-65" href="mail">
            <span class="pm-icon-wrap"><span class="pm-icon ui-icon-pencil"></span></span>发件
        </a>
        <span class="pm-navi-item"><a href="guest?feed">收件</a></span>
        <span class="pm-navi-item"><a href="chan?plaza">广场</a></span>
    </td><td class="pm-search-bar">
        <form class="pm-search-form pm-border pm-corner-all" method="get" action="search">
            <input type="text" name="keyword" id="keyword" value="搜频道、邮件" class="pm-no-border">
            </input><input type="submit" id="search" value=""></input>
        </form>
    </td><td class="pm-manage-account">游客模式，访问受限&nbsp;{*登录后拥有更多权限*}
        <a class="pm-light-button pm-login">登录</a>&nbsp;|&nbsp;<a class="pm-light-button" href="regi">注册</a>
    </td></tr></table>
</div>

<link type="text/css" title="style" href="css/jquery.ui.core.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/jquery.ui.theme.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/jquery.ui.resizable.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/jquery.ui.autocomplete.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.navi.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.login.css" rel="stylesheet"/>

<script type="text/JavaScript" src="js.min/jquery/jquery-1.7.min.js"></script>
<script type="text/JavaScript" src="js.min/jquery/jquery.ui.core.min.js"></script>
<script type="text/JavaScript" src="js.min/jquery/jquery.ui.widget.min.js"></script>
<script type="text/JavaScript" src="js.min/jquery/jquery.ui.mouse.min.js"></script>
<script type="text/JavaScript" src="js.min/jquery/jquery.ui.position.min.js"></script>
<script type="text/JavaScript" src="js.min/jquery/jquery.ui.draggable.min.js"></script>
<script type="text/JavaScript" src="js.min/jquery/jquery.ui.autocomplete.min.js"></script>
{*
<script type="text/JavaScript" src="js.min/jquery/jquery.ui.resizable.min.js"></script>
*}

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
<script type="text/JavaScript" src="js.min/jquery/jquery.cookie.min.js"></script>
*}

<script type="text/JavaScript" src="js/core/pmail.core.js"></script>
<script type="text/JavaScript" src="js.min/user/pmail.login.min.js"></script>
