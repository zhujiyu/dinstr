<div class="pm-message pm-message-user" id="{$message.message_id}" relation="{$message.ID}" new="{$message.new_message}" friend="{$message.friend_id}">
    <div class="pm-message-avatar pm-inline-block">
        {include file="user/pmail.user.avatar.tpl" user=$message.friend type="pm-avatar-middle"}
    </div><div class="pm-message-body pm-inline-block">
        <div class="content">
            {if $user.ID == $message.send_id}发送给{/if}
            <a id="user" href="user?id={$message.friend.ID}">{$message.friend.username}</a>：{$message.message}
        </div>
        <table class="pm-layout-table"><tr>
            <td class="pm-time" time="{$message.create_time}"></td>
            <td class="pm-ctrl">
                <a id="list" href="msg?ur={$message.friend.ID}">
                    {if $message.new_message > 1}有{$message.new_message-1}条新私信{else}共{$message.message_num}条私信{/if}
                </a> &nbsp;|&nbsp;<a id="reply">回复</a> &nbsp;|&nbsp;<a id="delete">删除</a>
            </td>
        </tr></table>
    </div>
</div>