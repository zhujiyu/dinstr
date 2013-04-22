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

        <div class="pm-mail-list pm-more-list" item="user-mail" extname="target_id" extvalue="{$target_user.ID}">
            {section name=pmi loop=$pub_mails}
                <div class="pm-content-border"></div>
                {include file="mail/pmail.mail.tpl" mail=$pub_mails[pmi]}
            {sectionelse}
                <div class="pm-empty-content">{$target_user.username}没有公开的邮件！</div>
            {/section}
        </div>
    </div><div class="pm-page-right pm-inline-block">
        {include file="user/pmail.user.follow.tpl"}
        <div class="pm-content-border"></div>
        {include file="user/pmail.user.param.tpl" param=$target_user.param}
        <div class="pm-content-border"></div>
        {include file="comm/pmail.idea.tpl"}
    </div> <!-- end of page -->

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

{if $user}
    {include file="comm/pmail.navi.tpl"}
    {include file="comm/pmail.edit.tpl"}
{else}
    {include file="comm/pmail.guest.top.tpl"}
    <link type="text/css" title="style" href="css/pmail.page.css" rel="stylesheet"/>
    <link type="text/css" title="style" href="css/pmail.mail.css" rel="stylesheet"/>
    <link type="text/css" title="style" href="css/pmail.chan.css" rel="stylesheet"/>
    <script type="text/JavaScript" src="js.min/mail/pmail.good.min.js"></script>
{/if}
<link type="text/css" title="style" href="css/pmail.user.css" rel="stylesheet"/>

<script type="text/JavaScript" src="js.min/core/pmail.date.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.chan.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.chan.tip.min.js"></script>
<script type="text/JavaScript" src="js.min/user/pmail.user.min.js"></script>
<script type="text/JavaScript" src="js.min/user/pmail.message.min.js"></script>

<script type="text/JavaScript" src="js.min/mail/pmail.mail.min.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.mail.manage.min.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.theme.min.js"></script>

</body>
</html>