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
    {elseif $notices[ni].type == 'apply'}
        {if $notices[ni].apply_user_id == $user.ID}
        你加入<a href="chan?id={$notices[ni].channel_id}">{$notices[ni].channel}</a>频道的申请{if $notices[ni].status == 'accept'}已经通过{elseif $notices[ni].status == 'refuse'}被拒绝{/if}
        {else}
        <a href="user?id={$notices[ni].apply_user_id}">{$notices[ni].apply_username}</a>申请加入<a href="chan?id={$notices[ni].channel_id}">{$notices[ni].channel}</a>频道
        {/if}
    {/if}
    <span class="date pm-time" time="{$notices[ni].create_time}">{$notices[ni].create_time|date_ago:"m-d H:i"}</span>
    </div>
    {if !$smarty.section.ni.last}<div class="pm-border-line"></div>{/if}
{sectionelse}
    <div class="pm-empty-content">没有通知</div>
{/section}
</div>