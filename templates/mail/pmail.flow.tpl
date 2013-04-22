<div class="pm-mail" id="{$mail.ID}" flow="{$mail.flow.ID}" channel="{$mail.flow.channel_id}" user="{$mail.flow.user_id}">
    <div class="pm-mail-avatar pm-inline-block">
        {include file="user/pmail.user.avatar.tpl" user=$mail.flow.user type="pm-avatar-middle"}
    </div><div class="pm-mail-body pm-inline-block">
        {include file="mail/pmail.mail.top.tpl" user=$mail.user channel=$mail.flow.channel}
        {include file="mail/pmail.theme.title.tpl" theme=$mail.theme flow=$mail.flow}
        {include file="mail/pmail.mail.info.tpl"}
        {include file="mail/pmail.mail.bottom.tpl" mailuser=$mail.flow.user}
    </div>
</div>