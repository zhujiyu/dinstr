<div class="pm-channel pm-object-item" id="{$channel.ID}" weight="{$channel.member.weight}"
    rank="{$channel.member.rank}"><div class="pm-object-avatar pm-inline-block">
        {include file="chan/pmail.channel.avatar.tpl" type="pm-avatar-middle"}
    </div><div class="pm-object-info pm-channel-info pm-inline-block">
        <a class="pm-channel-title" href="chan?id={$channel.ID}">{$channel.name}</a>
        {include file="chan/pmail.channel.type.tpl"}
        <div class="pm-channel-param">
            成员 <span>{$channel.member_num}</span>
            订阅 <span>{$channel.subscriber_num}</span>
            邮件 <span>{$channel.mail_num}</span>
        </div>
        <div class="pm-channel-tag-list">
            {section name=tagi loop=$channel.tags}
                <a href="collect?item=channel&tag={$channel.tags[tagi].ID}">{$channel.tags[tagi].tag}</a>
            {/section}
        </div>
        <div class="pm-channel-desc">{$channel.description}</div>
    </div><div class="pm-ctrl pm-channel-ctrl pm-inline-block" id="{$channel.ID}" member_id="{$channel.member.ID}">
        {if $channel.member.role > 1}
            <div class="role">频道管理员</div>
            <div class="manage"><a class="pm-edit" href="chan?id={$channel.ID}&p=edit">编辑频道</a></div>
        {elseif $channel.member.role == 1}
            <div class="role">频道成员</div>
            <div class="manage"><a class="pm-quit">退出频道</a></div>
        {elseif $channel.member.role == 0}
            <div class="role">已订阅</div>
            <div class="manage"><a class="pm-apply-join">申请加入</a></div>
            <div class="manage"><a class="pm-subscribe-cancel">取消订阅</a></div>
        {else}
            <div class="manage"><a class="pm-apply-join">申请加入</a></div>
            <div class="manage"><a class="pm-subscribe">订阅频道</a></div>
        {/if}
    </div>
</div>