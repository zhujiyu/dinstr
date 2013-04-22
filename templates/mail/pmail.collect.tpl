<div class="pm-mail" id="{$mail.ID}" parent="{$mail.parent}" flow="{$mail.flow.ID}" channel="{$mail.flow.channel_id}">
    <div class="pm-mail-avatar pm-inline-block">
        {include file="user/pmail.user.avatar.tpl" user=$mail.user type="pm-avatar-middle"}
    </div><div class="pm-mail-body pm-inline-block">
        {include file="mail/pmail.mail.top.tpl" user=$mail.user channel=$mail.channel_list[0]}
        {include file="mail/pmail.theme.title.tpl" theme=$mail.theme flow=$mail.channel_list[0]}
        {include file="mail/pmail.mail.info.tpl"}
        <div class="pm-mail-bottom">
            <span class="depth" depth="{$mail.depth}">{$mail.depth}楼</span>
            <a href="mail?id={$mail.ID}"><span class="date pm-time" time="{$mail.create_time}">{$mail.create_time|date_ago:"m-d H:i"}</span></a>
            <span class="pm-mail-ctrl pm-ctrl">
                {if $user && $mail.flow.user.ID == $user.ID}<a class="delete">删除</a>{/if}
                <a class="reply">回复(<span>{$mail.reply_num}</span>)</a>
                {if $mail.depth}<a class="parent">历史(<span>{$mail.depth}</span>)</a>{/if}
                <span class="collected pm-inline-block pm-icon-wrap" title="取消收藏"><span class="pm-icon ui-icon-heart"></span></span>
            </span>
        </div>
    </div>
</div>
