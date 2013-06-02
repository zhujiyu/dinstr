<div class="dis-chan dis-chan-simple" id="{$chan.ID}" title="{$chan.name}">
    <div class="dis-chan-logo dis-inline-block">
        <div class="dis-avatar-img dis-tile-img dis-load-display dis-avatar-middle" imgsrc="{$chan.logo.small}" title="{$chan.name}">
            <a href="chan?id={$chan.ID}"></a>
        </div>
    </div><div class="dis-chan-info dis-inline-block">
        <div class="dis-chan-title dis-module-title">
            <a href="chan?id={$chan.ID}">{$chan.name}</a>
        </div>
        <div class="dis-chan-param">
            <a href="chan?id={$chan.ID}">会员：<span>{$chan.member_num}</span></a>
            <a href="chan?id={$chan.ID}">订阅：<span>{$chan.subscriber_num}</span></a>
            <a href="chan?id={$chan.ID}">资讯：<span>{$chan.mail_num}</span></a>
        </div>
    </div>
    <div class="dis-chan-detail">
        <div class="dis-tags-list">
            {section name=tagi loop=$chan.tags max=4}
                <a href="collect?item=channel&tag={$chan.tags[tagi].ID}">{$chan.tags[tagi].tag}</a>
            {/section}
        </div>
        <div class="dis-chan-desc">{$chan.description|escape|truncate:75:'...'}</div>
    </div>
    <div class="dis-ctrl dis-chan-ctrl" id="{$chan.ID}">
    {if $chan.member.role > 1}
        <a class="dis-quit">退出频道</a>
        <a class="dis-edit">编辑频道</a>
    {elseif $chan.member.role == 1}
        <a class="dis-quit">退出频道</a>
    {elseif $chan.member.role == 0}
        <a class="dis-apply-join">加入</a>
        <a class="dis-follow-cancel">取消关注</a>
    {else}
        <a class="dis-apply-join">加入</a>
        <a class="dis-follow">关注</a>
    {/if}
    </div>
</div>
