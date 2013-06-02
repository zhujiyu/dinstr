<div class="pm-channel pm-channel-simple" id="{$channel.ID}" title="{$channel.name}">
    <div class="pm-channel-logo pm-inline-block">
        <div class="pm-avatar-img pm-tile-img pm-load-display pm-avatar-middle" imgsrc="{$channel.logo.small}" title="{$channel.name}">
            <a href="chan?id={$channel.ID}"></a>
        </div>
    </div>
    <div class="pm-channel-info pm-inline-block">
        <div class="pm-channel-title pm-module-title">
            <a href="chan?id={$channel.ID}">{$channel.name}</a>
        </div>
        <div class="pm-channel-param">
            <a href="chan?id={$channel.ID}">会员：<span>{$channel.member_num}</span></a>
            <a href="chan?id={$channel.ID}">订阅：<span>{$channel.subscriber_num}</span></a>
            <a href="chan?id={$channel.ID}">资讯：<span>{$channel.mail_num}</span></a>
        </div>
    </div>
    <div class="pm-channel-detail">
        <div class="pm-tags-list">
            {section name=tagi loop=$channel.tags max=4}
                <a href="collect?item=channel&tag={$channel.tags[tagi].ID}">{$channel.tags[tagi].tag}</a>
            {/section}
        </div>
        <div class="pm-channel-desc">{$channel.description|escape|truncate:75:'...'}</div>
    </div>
    <div class="pm-ctrl pm-channel-ctrl" id="{$channel.ID}">
    {if $channel.member.role > 1}
        <a class="pm-quit">退出频道</a>
        <a class="pm-edit">编辑频道</a>
    {elseif $channel.member.role == 1}
        <a class="pm-quit">退出频道</a>
    {elseif $channel.member.role == 0}
        <a class="pm-apply-join">加入</a>
        <a class="pm-follow-cancel">取消关注</a>
    {else}
        <a class="pm-apply-join">加入</a>
        <a class="pm-follow">关注</a>
    {/if}
    </div>
</div>
