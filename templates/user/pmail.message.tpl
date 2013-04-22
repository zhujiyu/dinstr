<div class="pm-message" id="{$message.message_id}" relation="{$message.relation_id}">
    <div class="pm-message-avatar pm-inline-block">
    {if $message.send_id == $user.ID}
        {include file="user/pmail.user.avatar.tpl" user=$user type="pm-avatar-middle"}
    {else}
        {include file="user/pmail.user.avatar.tpl" user=$friend type="pm-avatar-middle"}
    {/if}
    </div><div class="pm-message-body pm-inline-block">
        <div class="content">
            {if $message.send_id == $user.ID}我{else}<a id="user" href="user?id={$friend.ID}">{$friend.username}</a>{/if}
            ：{$message.message}
        </div>
        <table class="pm-layout-table"><tr>
            <td class="pm-time" time="{$message.create_time}">{$message.create_time|date_ago:"m-d H:i"}</td>
            <td class="pm-ctrl">
                {if $message.send_id != $user.ID}<a id="reply">回复</a> &nbsp;|&nbsp;{/if}<a id="delete">删除</a>
            </td>
        </tr></table>
    </div>
</div>