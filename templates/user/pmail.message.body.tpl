    <div class="so-message-body so-inline-block">
        <div class="content">
            {if $user.ID == $message.send_id}我发送给{/if}
            <a href="user?id={$message.friend.ID}">{$message.friend.username}</a>：{$message.message}
        </div>
        <table class="so-layout-table"><tr>
            <td class="so-time" time="{$message.create_time}"></td>
            <td class="so-ctrl">
                <a id="list" href="message?p=list&relation={$message.ID}">
                    {if $message.new_message > 1}有{$message.new_message-1}条新私信{else}共{$message.message_num}条私信{/if}
                </a> &nbsp;|&nbsp;
                <a id="reply">回复</a> &nbsp;|&nbsp;
                <a id="delete">删除</a>
            </td>
        </tr></table>
    </div>
