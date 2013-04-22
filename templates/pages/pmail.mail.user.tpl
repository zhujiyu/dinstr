{include file="comm/pmail.header.tpl"}
<body>

{if $notices}<div class="pm-notice-wrap"><div class="pm-fixed-notices">
    <div class="pm-approve-notices pm-notice-item">
        {section name=i loop=$notices.approves}
            <a id="{$notices.approves[i].data_id}">{$notices.approves[i].mail_username}</a>
        {/section}
    </div>
</div></div>{/if}

<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

    <div class="pm-page-left pm-inline-block">
        <div class="pm-theme-title" id="{$theme.ID}">
            <a class="title" href="mail?id={$theme.mail_id}">
                {if $mail.channel_list && $mail.channel_list[0].weight}{$mail.channel_list[0].weight}&nbsp;@&nbsp;{/if}{$theme.content|escape:3}
            </a>
        </div>

            {*
            <div class="pm-theme pm-theme-global" id="{$theme.ID}" opened="{$theme.opened}">
                <div class="pm-theme-title pm-title">{$theme.content|truncate:100:'...'}</div>
                <div class="pm-theme-mail pm-mail">{include file="mail/pmail.mail.info.tpl" mail=$theme.mail type="pm-avatar-middle"}</div>
            </div>*}

        <div class="pm-content-border"></div>
        <div class="pm-module-title">
            {if $view == 'interest'}关注该话题的人{else}参与该话题的人{/if}
        </div>
        <div class="pm-content-border"></div>

        {if $view == 'interest' || $view == 'approval'}
            <div class="pm-list pm-user-list">
                {section name=ui loop=$user_list}
                    {include file="user/pmail.user.tpl" user=$user_list[ui] logUser=$user}
                    {if !$smarty.section.ui.last}<div class="pm-border-line"></div>{/if}
                {sectionelse}
                    <div class="pm-empty-content">没有用户</div>
                {/section}
            </div>
        {/if}
    </div><div class="pm-page-right pm-inline-block">
        <div class="pm-theme pm-theme-ctrl pm-page-ctrl" id="{$theme.ID}">
            {if $theme.status.interest}
                <a class="pm-interest-cancel pm-gray-button"><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-pin-w"></span></span>&nbsp;取消关注</a>
            {else}
                <a class="pm-interest pm-light-button"><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-pin-s"></span></span>&nbsp;关注</a>
            {/if}
            {if $theme.status.approved}
                <a class="pm-approve-cancel pm-gray-button"><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-cancel"></span></span>&nbsp;取消参与</a>
            {else}
                <a class="pm-approve pm-light-button"><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-heart"></span></span>&nbsp;参与</a>
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
            <div class="pm-module-title">邮件发布频道</div>
            {include file="chan/pmail.channel.simple.tpl" channel=$mail.channel_list[0]}
            <div class="pm-content-border"></div>
        {/if}

        <div class="pm-module-title">邮件类型</div>
        {if $theme.type}<a href="theme?type={$theme.type}">{$theme.type}</a>{else}未设置类型{/if}
        <div class="pm-theme-list"></div>
        <div class="pm-content-border"></div>

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

<link type="text/css" title="style" href="css/pmail.page.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.mail.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.chan.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.user.css" rel="stylesheet"/>

<script type="text/JavaScript" src="js.min/user/pmail.user.min.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.mail.min.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.theme.min.js"></script>

</body>
</html>