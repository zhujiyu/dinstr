{include file="comm/pmail.header.tpl"}
<body>

{if $notices}<div class="pm-notice-wrap"><div class="pm-fixed-notices">
    {if $notices.mails}<div class="pm-mail-notices pm-notice-item">
        <span>{$notices.mails|@count}人回复了该邮件，按用户查看：</span>
        {section name=nmi loop=$notices.mails}
            <a id="{$notices.mails[nmi].data_id}">{$notices.mails[nmi].mail_username}</a>
        {/section}
    </div>{/if}

    {if $notices.replies}<div class="pm-reply-notices pm-notice-item">
        {$notices.replies|@count}人回复了你，按用户查看：
        {section name=i loop=$notices.replies}
            <a id="{$notices.replies[i].data_id}">{$notices.replies[i].mail_username}</a>
        {/section}
    </div>{/if}
</div></div>{/if}

<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

    <div class="pm-page-left pm-theme-page pm-inline-block">
        <div class="pm-theme pm-theme-global" id="{$theme.ID}" opened="{$theme.opened}">
            <div class="pm-theme-title"><a class="title" href="mail?id={$theme.mail_id}">
                {if $mail.channel_list && $mail.channel_list[0].weight}{$mail.channel_list[0].weight}&nbsp;@&nbsp;{/if}{$theme.content|escape:3}
            </a></div>
            <div class="pm-border-line"></div>

<div class="pm-mail-content pm-mail-content-big">
    <div class="pm-text-content">
        <span class="content">{$mail.content}</span>
    </div>
    {if $mail.objects}
    <div class="pm-object-list">
    {section name=oi loop=$mail.objects}
        {if $mail.objects[oi].type == 'good'}
            {include file="good/pmail.good.tpl" good=$mail.objects[oi].good desc=$mail.objects[oi].desc}
        {elseif $mail.objects[oi].type == 'photo'}
        <div class="pm-photo pm-inline-block">
            <div class="pm-photo-img pm-load-display" bigimg="{$mail.objects[oi].photo.big}" smlimg="{$mail.objects[oi].photo.small}"><img /></div>
            <div class="pm-photo-desc">{$mail.objects[oi].desc}(图片由<a href="user?id={$mail.objects[oi].photo.user_id}">@{$mail.objects[oi].photo.user.username}</a>上传)</div>
        </div>
        {/if}
        {if !$smarty.section.oi.last}<div class="pm-border-line pm-inline-block"></div>{/if}
    {/section}<div class="pm-shirt-v pm-inline-block"></div>
    </div>
    {/if}
</div>
            {*<div class="pm-theme-mail pm-mail" id="{$mail.ID}">
                {include file="mail/pmail.mail.info.tpl"}
            </div>*}
        </div>
        <div class="pm-content-border"></div>

        <div class="pm-page-navi pm-range-navi" view="{$range}">
            <div class="pm-module-title pm-inline-block">时间线</div>
            <a class="default all item" href="home?feed">全部显示</a>
            <a class="follow item" href="home?feed&follow">可信任的</a>
        </div>

        <div class="pm-list pm-mail-list">
            {section name=mli loop=$mail_list}
                {include file="mail/pmail.mail.theme.tpl" mail=$mail_list[mli] type="pm-avatar-small"}
                {if !$smarty.section.mli.last}<div class="pm-border-line"></div>{/if}
            {/section}
        </div>
    </div><div class="pm-page-right pm-inline-block">
        <div class="pm-theme pm-theme-ctrl pm-page-ctrl" id="{$theme.ID}">
            {if $theme.status.interest}
                <a class="pm-interest-cancel pm-gray-button pm-inline-block"><span class="pm-inline-block pm-icon-wrap"><span class="pm-icon ui-icon-pin-w"></span></span>&nbsp;取消关注</a>
            {else}
                <a class="pm-interest pm-light-button pm-inline-block"><span class="pm-inline-block pm-icon-wrap"><span class="pm-icon ui-icon-pin-s"></span></span>&nbsp;关注</a>
            {/if}
            {if $theme.status.approved}
                <a class="pm-approve-cancel pm-gray-button pm-inline-block"><span class="pm-inline-block pm-icon-wrap"><span class="pm-icon ui-icon-cancel"></span></span>&nbsp;取消参与</a>
            {else}
                <a class="pm-approve pm-light-button pm-inline-block"><span class="pm-inline-block pm-icon-wrap"><span class="pm-icon ui-icon-heart"></span></span>&nbsp;参与</a>
            {/if}
        </div>

        <div class="pm-content-border"></div>
        <div class="pm-theme-stats pm-param">
            <a class="pm-inline-block" href="mail?id={$theme.mail_id}&view=mail"><span class="data">{$theme.mail_num-1}</span>回复</a>
            <span class="pm-vertical-border pm-inline-block"></span>
            <a class="pm-inline-block" href="mail?id={$theme.mail_id}&view=interest"><span class="data">{$theme.interest_num}</span>关注</a>
            <span class="pm-vertical-border pm-inline-block"></span>
            <a class="pm-inline-block" href="mail?id={$theme.mail_id}&view=approval"><span class="data">{$theme.approved_num}</span>参与</a>
        </div>
        <div class="pm-content-border"></div>

        {include file="user/pmail.user.simple.tpl" user=$mail.user}
        <div class="pm-content-border"></div>

        {if $mail.channel_list}
        <div class="pm-module-title">邮件频道</div>
        {include file="chan/pmail.channel.simple.tpl" channel=$mail.channel_list[0]}
        <div class="pm-content-border"></div>
        {/if}

        <div class="pm-module-title">相关邮件</div>
        <div class="pm-theme-list"></div>
    </div>

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div><!-- end of page --> </div> <!-- end of wrap -->

{if $user}
    {include file="comm/pmail.navi.tpl"}
{else}
    {include file="comm/pmail.guest.top.tpl"}
{/if}
{include file="comm/pmail.edit.tpl"}

{*
<link type="text/css" title="style" href="css/pmail.page.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.mail.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.channel.css" rel="stylesheet"/>

<script type="text/JavaScript" src="js.min/core/pmail.photo.min.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.mail.edit.min.js"></script>
*}

<script type="text/JavaScript" src="js.min/core/pmail.date.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.chan.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.chan.tip.min.js"></script>

<script type="text/JavaScript" src="js.min/mail/pmail.mail.manage.min.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.mail.min.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.theme.min.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.publish.min.js"></script>

</body>
</html>