<div class="pm-channel pm-channel-card pm-inline-block ui-corner-all" id="{$channel.ID}" title="{$channel.name}">
    <div class="pm-channel-logo pm-inline-block">
        <div class="pm-avatar-img pm-tile-img pm-load-display pm-avatar-big" imgsrc="{$channel.logo.big}" title="{$channel.name}">
            <a href="chan?id={$channel.ID}"></a>
        </div>
    </div><div class="pm-channel-info pm-inline-block">
        {include file="chan/pmail.channel.type.tpl"}
        <div class="pm-channel-title pm-module-title">
            <a href="chan?id={$channel.ID}">{$channel.name|truncate_utf:10:'...'}</a>
        </div>
        <div class="pm-channel-url">
            <a href="chan?id={$channel.ID}">{if $channel.domain}{$channel.domain}{else}{$channel.ID}{/if}.{$app.url}</a>
        </div>
        <div class="pm-channel-param">
            嘉宾：{$channel.member_num}
            粉丝：{$channel.subscriber_num}
            资讯：{$channel.mail_num}
        </div>
        <div class="pm-tags-list">
            {section name=tagi loop=$channel.tags max=6}
                <a href="collect?item=channel&tag={$channel.tags[tagi].ID}">{$channel.tags[tagi].tag}</a>
            {sectionelse}无标签
            {/section}
        </div>
    </div>

    <div class="pm-channel-desc">
        {$channel.description|truncate_utf:50:'...'}
    </div>

    <div class="pm-ctrl pm-channel-ctrl" id="{$channel.ID}">
    {if $channel.role == 'editor'}
        <a class="pm-edit">编辑频道</a>
    {elseif $channel.role == 'member'}
        <a class="pm-quit">退出频道</a>
    {elseif $channel.role == 'subscriber'}
        <a class="pm-apply-join">加入频道</a>
        <a class="pm-subscribe-cancel">取消订阅</a>
    {else}
        <a class="pm-apply-join">加入频道</a>&nbsp;|&nbsp;
        <a class="pm-subscribe">订阅频道</a>
    {/if}
    </div>
</div>