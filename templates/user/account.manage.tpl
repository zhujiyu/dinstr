<div class="dis-corner-all dis-menu-list">
    <div class="dis-navi-item dis-user-name dis-corner-top">
        <a href="user">
            <span class="dis-avatar-img dis-avatar-small dis-tile-img dis-load-display dis-inline-block" imgsrc="{$user.avatar.small}"></span>
            <span class="username">{$user.username|truncate_utf:10:'...'}</span>
            <span class="dis-icon-wrap dis-inline-block"><span class="dis-icon ui-icon-triangle-1-s"></span></span>
        </a>
    </div>
    <div class="dis-border-line"></div>
    <div class="dis-navi-item dis-user-home">
        <a href="user"><span class="dis-icon-wrap dis-inline-block"><span class="dis-icon ui-icon-person"></span></span>我的主页</a>
    </div>
    <div class="dis-border-line"></div>
    <div class="dis-navi-item dis-user-imoney">
        <a href="imoney"><span class="dis-icon-wrap dis-inline-block"><span class="dis-icon ui-icon-person"></span></span>充值</a>
    </div>
    <div class="dis-border-line"></div>
    <div class="dis-navi-item dis-user-message">
        <a href="msg"><span class="dis-icon-wrap dis-inline-block"><span class="dis-icon ui-icon-mail-closed"></span></span>私信 </a>
    </div>
    <div class="dis-border-line"></div>
    <div class="dis-navi-item pm-user-setting">
        <a href="user?p=edit"><span class="dis-icon-wrap dis-inline-block"><span class="dis-icon ui-icon-wrench"></span></span>设置</a>
    </div>
    <div class="dis-border-line"></div>
    <div class="dis-navi-item pm-user-logout dis-corner-bottom">
        <a href="index?p=logout"><span class="dis-icon-wrap dis-inline-block"><span class="dis-icon ui-icon-power"></span></span>退出</a>
    </div>
</div>
