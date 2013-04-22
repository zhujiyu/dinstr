<div class="pm-mail-bottom">
    <span class="depth" depth="{$mail.depth}">{$mail.depth}楼</span>
    <a href="mail?id={$mail.ID}">
        <span class="date pm-date" time="{$mail.create_time}"></span>
    </a>
    <a class="reply">回复(<span>{$mail.reply_num}</span>)</a>
    {if $mail.depth > 0}<a class="parent">历史(<span>{$mail.depth}</span>)</a>{/if}
    {if $user && $mailuser.ID == $user.ID}<a class="delete">删除</a>{/if}
    <span class="collect pm-icon-wrap" title="收藏"><span class="pm-icon ui-icon-heart"></span></span>
</div>

    {*<!--
    <span class="pm-ctrl">
        <a class="reply">回复(<span>{$mail.reply_num}</span>)</a>{*&nbsp;<a class="forward">抄送</a>&nbsp; *
        {if $mail.depth > 0}<a class="parent">历史(<span>{$mail.depth}</span>)</a>{/if}
        {if $user && $mailuser.ID == $user.ID}<a class="delete">删除</a>{/if}
        <span class="collect pm-inline-block pm-icon-wrap" title="收藏"><span class="pm-icon ui-icon-heart"></span></span>
    </span>
    -->*}