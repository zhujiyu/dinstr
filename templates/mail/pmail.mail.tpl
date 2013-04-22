<div class="pm-mail" id="{$mail.ID}" user="{$mail.user.ID}">
    <div class="pm-mail-avatar pm-inline-block">
        {include file="user/pmail.user.avatar.tpl" user=$mail.user type="pm-avatar-middle"}
    </div><div class="pm-mail-body pm-inline-block">
        {include file="mail/pmail.mail.top.tpl" user=$mail.user channel=$mail.channel_list[0]}
        {include file="mail/pmail.theme.title.tpl" theme=$mail.theme flow=$mail.channel_list[0]}
        {include file="mail/pmail.mail.info.tpl"}
        {include file="mail/pmail.mail.bottom.tpl" mailuser=$mail.user}
    </div>
</div>