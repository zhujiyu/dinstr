<div class="dis-top-navi">
    <table class="dis-layout-table dis-navi-content"><tr>
        <td class="dis-navi-list">
            <a class="dis-navi-item" href="guest?feed">购物</a>
            <a class="dis-navi-item" href="chan?plaza">生活</a>
            <a class="dis-navi-item" href="chan?plaza">职场</a>
            <a class="dis-navi-item" href="chan?plaza">游戏</a>
            <a class="dis-navi-item" href="chan?plaza">社交</a>
        </td><td class="dis-manage-account">
            {if $user}
                <div class="ui-corner-all dis-menu-list">
                    <a class="dis-navi-item dis-user-name ui-corner-top" href="user">
                        <span class="dis-avatar-icon dis-tile-img dis-load-display dis-inline-block"
                                  imgsrc="{$user.avatar.small}"></span>
                        <span class="username">{$user.username|truncate_utf:10:'...'}</span>
                        <span class="dis-icon-wrap dis-inline-block"><span class="dis-icon ui-icon-triangle-1-s"></span></span>
                    </a>
                    <div class="dis-border-line"></div>
                    <a class="dis-navi-item dis-user-home" href="user">
                        <span class="dis-icon-wrap dis-inline-block"><span class="dis-icon ui-icon-person"></span></span>我的主页
                    </a>
                    <div class="dis-border-line"></div>
                    <a class="dis-navi-item dis-user-imoney" href="imoney">
                        <span class="dis-icon-wrap dis-inline-block"><span class="dis-icon ui-icon-person"></span></span>充值
                    </a>
                    <div class="dis-border-line"></div>
                    <a class="dis-navi-item dis-user-message" href="msg">
                        <span class="dis-icon-wrap dis-inline-block"><span class="dis-icon ui-icon-mail-closed"></span></span>私信
                    </a>
                    <div class="dis-border-line"></div>
                    <a class="dis-navi-item pm-user-setting" href="user?p=edit">
                        <span class="dis-icon-wrap dis-inline-block"><span class="dis-icon ui-icon-wrench"></span></span>设置
                    </a>
                    <div class="dis-border-line"></div>
                    <a class="dis-navi-item pm-user-logout ui-corner-bottom" href="index?p=logout">
                        <span class="dis-icon-wrap dis-inline-block"><span class="dis-icon ui-icon-power"></span></span>退出
                    </a>
                </div>
            {else}
                游客模式，访问受限<a class="dis-navi-item" href="login">登录</a>|<a class="dis-navi-item" href="regi">注册</a>
            {/if}
        </td>
    </tr></table>
</div>

{if $err}
    <div class="dis-err">
        <div class="dis-content">{$err}</div>
        <div class="dis-content-border"></div>
    </div>
{/if}

<link type="text/css" title="style" href="css/jquery.ui.core.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/jquery.ui.theme.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/dinstr.core.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/dinstr.navi.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/dinstr.page.css" rel="stylesheet"/>

{*<div class="dis-page-head dis-navi-content">
    <a class="dis-inline-block dis-navi-logo" href="index">
        {include file="comm/logo.comm.tpl"}
    </a>
    <div class="dis-inline-block">
        <h3>&nbsp;此处放置推荐的频道给用户</h3>
    </div>
    <div class="dis-content-border"></div>
    {if $err}
        <div class="dis-err">
            <div class="dis-content">{$err}</div>
            <div class="dis-content-border"></div>
        </div>
    {/if}
</div>*}

    {*<div class="dis-navi-content">
        <div class="dis-manage-account">
            {if $user}
                <div class="ui-corner-all dis-menu-list">
                    <a class="dis-navi-item dis-user-name ui-corner-top" href="user">
                        <span class="dis-avatar-icon dis-tile-img dis-load-display dis-inline-block"
                                  imgsrc="{$user.avatar.small}"></span>
                        <span class="username">{$user.username|truncate_utf:10:'...'}</span>
                        <span class="dis-icon-wrap dis-inline-block"><span class="dis-icon ui-icon-triangle-1-s"></span></span>
                    </a>
                    <div class="dis-border-line"></div>
                    <a class="dis-navi-item dis-user-home" href="user">
                        <span class="dis-icon-wrap dis-inline-block"><span class="dis-icon ui-icon-person"></span></span>我的主页
                    </a>
                    <div class="dis-border-line"></div>
                    <a class="dis-navi-item dis-user-imoney" href="imoney">
                        <span class="dis-icon-wrap dis-inline-block"><span class="dis-icon ui-icon-person"></span></span>充值
                    </a>
                    <div class="dis-border-line"></div>
                    <a class="dis-navi-item dis-user-message" href="msg">
                        <span class="dis-icon-wrap dis-inline-block"><span class="dis-icon ui-icon-mail-closed"></span></span>私信
                    </a>
                    <div class="dis-border-line"></div>
                    <a class="dis-navi-item pm-user-setting" href="user?p=edit">
                        <span class="dis-icon-wrap dis-inline-block"><span class="dis-icon ui-icon-wrench"></span></span>设置
                    </a>
                    <div class="dis-border-line"></div>
                    <a class="dis-navi-item pm-user-logout ui-corner-bottom" href="index?p=logout">
                        <span class="dis-icon-wrap dis-inline-block"><span class="dis-icon ui-icon-power"></span></span>退出
                    </a>
                </div>
            {else}
                <span>游客模式，访问受限&nbsp;&nbsp;<a class="dis-navi-item" href="login">登录</a>&nbsp;|&nbsp;<a class="dis-navi-item" href="regi">注册</a></span>
            {/if}
        </div>
        <div class="dis-navi-list">
            <a class="dis-navi-item" href="guest?feed">购物</a>
            <a class="dis-navi-item" href="chan?plaza">生活</a>
            <a class="dis-navi-item" href="chan?plaza">职场</a>
            <a class="dis-navi-item" href="chan?plaza">游戏</a>
            <a class="dis-navi-item" href="chan?plaza">社交</a>
        </div>
    </div>*}
