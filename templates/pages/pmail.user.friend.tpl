{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

    <!-- 主体部分 -->
    <div class="pm-page-left pm-inline-block">
<div class="pm-user pm-user-main" id="{$target_user.ID}">
    <div class="pm-user-avatar pm-inline-block">
        <div class="pm-avatar-img pm-load-display pm-tile-img pm-avatar-big" imgsrc="{$target_user.avatar.big}"></div>
    </div><div class="pm-user-info pm-inline-block">
        <div class="pm-user-name"><a href="?id={$target_user.ID}">{$target_user.username}</a></div>
        <div class="pm-user-link"><a href="user?id={$target_user.ID}">{$app.url}/user?u={$target_user.username}</a></div>
        <div class="pm-user-param">
            <span class="pm-veri-data pm-inline-block"><span class="pm-veri pm-icon ui-icon-person"></span></span>
            {$target_user.live_city} {$target_user.contact}
        </div>
        <div class="pm-user-desc">{if $target_user.self_intro}{$target_user.self_intro|escape}{else}他没有填写个人介绍..{/if}</div>
    </div>
</div>

        <div class="pm-module-title">关注我的人</div>
        <div class="pm-object-list pm-user-list">
            {section name=fui loop=$fan_users}
                <div class="pm-content-border"></div>
                {include file="user/pmail.user.tpl" user=$fan_users[fui] logUser=$user}
            {sectionelse}
                <div class="pm-empty-content">没有任何人关注你</div>
            {/section}
        </div>
    </div><div class="pm-page-right pm-inline-block">
        {include file="user/pmail.user.follow.tpl"}
        <div class="pm-content-border"></div>
        {include file="user/pmail.user.param.tpl" param=$target_user.param}
        <div class="pm-content-border"></div>
        {include file="comm/pmail.idea.tpl"}
    </div>

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

{if $user}
    {include file="comm/pmail.navi.tpl"}
{else}
    {include file="comm/pmail.guest.top.tpl"}
{/if}
<link type="text/css" title="style" href="css/pmail.page.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.user.css" rel="stylesheet"/>
<script type="text/JavaScript" src="js.min/user/pmail.user.min.js"></script>
<script type="text/JavaScript" src="js.min/user/pmail.message.min.js"></script>

</body>
</html>