{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

    <div class="pm-page-left pm-notice-page">
            <div class="pm-page-navi" view="{$view}">
                <div class="pm-module-title">我的通知</div>
                <a class="default unread item" href="?p=unread">未读</a>
                <a class="all item" href="?p=all">全部</a>
            </div>

        <div class="pm-notice-list">
            {section name=ni loop=$notices}
                <div class="pm-notice-item" id="{$notices[ni].ID}">
                {if $notices[ni].type == 'mail'}
                    <a href="user?id={$notices[ni].mail_user_id}">{$notices[ni].mail_username}</a>回复了邮件<a href="mail?id={$notices[ni].theme_id}">{$notices[ni].theme}</a>
                {elseif $notices[ni].type == 'reply'}
                    <a href="user?id={$notices[ni].mail_user_id}">{$notices[ni].mail_username}</a>回复了你的邮件<a href="mail?id={$notices[ni].theme_id}">{$notices[ni].theme}</a>
                {elseif $notices[ni].type == 'approve'}
                    <a href="user?id={$notices[ni].approve_user_id}">{$notices[ni].approve_username}</a>参与了邮件<a href="mail?id={$notices[ni].theme_id}">{$notices[ni].theme}</a>
                {elseif $notices[ni].type == 'fan'}
                    <a href="user?id={$notices[ni].fan_user_id}">{$notices[ni].fan_username}</a>关注了你
                {/if}
                    <span class="date pm-time" time="{$notices[ni].create_time}">{$notices[ni].create_time|date_ago:"m-d H:i"}</span>
                </div>
                {if !$smarty.section.ni.last}<div class="pm-border-line"></div>{/if}
            {sectionelse}
                <div class="pm-empty-content">没有通知</div>
            {/section}
        </div>
    </div><div class="pm-page-right pm-inline-block">
            {include file="comm/pmail.right.navi.tpl"}
            <div class="pm-content-border"></div>
            {include file="comm/pmail.idea.tpl"}
    </div>

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

{include file="comm/pmail.navi.tpl"}
<link type="text/css" title="style" href="css/pmail.page.css" rel="stylesheet"/>

</body>
</html>