<div class="dis-channel dis-chan-card dis-inline-block" id="{$chan.ID}" title="{$chan.name}">
    <div class="dis-chan-logo dis-inline-block">
        <div class="dis-tile-img dis-load-display dis-avatar-middle" imgsrc="{$chan.logo.big}">
            <a href="chan?id={$chan.ID}"></a>
        </div>
    </div><div class="dis-chan-info dis-inline-block">
        {include file="chan/pmail.channel.type.tpl"}
        <a class="dis-module-title" href="chan?id={$chan.ID}">{$chan.name|truncate_utf:10:'...'}</a>
        {if $chan.tags}
        <div class="dis-tags-list">
            {section name=tagi loop=$chan.tags max=6}
                <a href="collect?item=chan&tag={$chan.tags[tagi].ID}">{$chan.tags[tagi].tag}</a>
            {/section}
        </div>
        {/if}
    </div>
        <div class="dis-chan-desc">
            {$chan.desc|truncate_utf:50:'...'}
        </div>

    <div class="dis-ctrl dis-chan-ctrl" id="{$chan.ID}">
    {if $chan.role == 'editor'}
        <a class="dis-edit">编辑</a>
    {elseif $chan.role == 'member'}
        <a class="dis-quit">退出</a>
    {elseif $chan.role == 'subscriber'}
        <a class="dis-apply-join">申请加入</a>
        <a class="dis-subscribe-cancel">取消订阅</a>
    {else}
        <a class="dis-apply-join">申请加入</a>&nbsp;|&nbsp;
        <a class="dis-subscribe">订阅</a>
    {/if}
    </div>
</div>
        {*<div class="dis-channel-url">
            <a href="chan?id={$chan.ID}">{if $chan.domain}{$chan.domain}{else}{$chan.ID}{/if}.{$app.url}</a>
        </div>
        <div class="dis-channel-title dis-module-title">
            <a href="chan?id={$chan.ID}">{$chan.name|truncate_utf:10:'...'}</a>
        </div>
        <div class="dis-chan-param">
            嘉宾：{$chan.member_num}
            粉丝：{$chan.subscriber_num}
            资讯：{$chan.mail_num}
        </div>*}
