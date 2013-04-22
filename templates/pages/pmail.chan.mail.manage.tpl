{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

    <div class="pm-page-left pm-inline-block">
        {include file="chan/pmail.chan.global.tpl"}
        <div class="pm-content-border"></div>

        <ul class="pm-page-navi" view="{$period}">
            <div class="pm-module-title pm-inline-block">价值线</div>
            {*<li class="6h item"><a href="chan?id={$channel.ID}&period=6h">6小时</a></li>*}
            <li class="default 1d item"><a href="chan?id={$channel.ID}&period=1d">一天</a></li>
            <li class="3d item"><a href="chan?id={$channel.ID}&period=3d">三天</a></li>
            <li class="week item"><a href="chan?id={$channel.ID}&period=week">一周</a></li>
            <li class="month item"><a href="chan?id={$channel.ID}&period=month">一月</a></li>
            <li class="timeline item"><a href="chan?id={$channel.ID}&sort=timeline">时间排序</a></li>
        </ul>

        <div class="pm-mail-list pm-more-list" item="channel-mail" extname="channel_id,period,flag" extvalue="{$channel.ID},{$period},{$flag}">
            {section name=mli loop=$mail_list}
                {include file="mail/pmail.flow.tpl" mail=$mail_list[mli]}
                {if !$smarty.section.mli.last}<div class="pm-content-border"></div>{/if}
            {/section}
        </div>
    </div><div class="pm-page-right pm-inline-block">
        <div class="pm-channel-ctrl pm-page-ctrl" id="{$channel.ID}">
                <a class="pm-gray-button" href="chan?id={$channel.ID}&p=edit"><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-wrench"></span></span>&nbsp;频道设置</a>
                <a class="pm-gray-button" href="chan?id={$channel.ID}&view=applicant"><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-person"></span></span>&nbsp;会员管理</a>
        </div>

        <div class="pm-content-border"></div>
        {include file="chan/pmail.channel.weight.tpl"}
        <div class="pm-content-border"></div>
        {include file="chan/pmail.channel.param.tpl"}

        <div class="pm-content-border"></div>
        <div class="pm-module-title">{$channel.name}的公告</div>
        <div class="pm-channel-announce pm-channel-ctrl" id={$channel.ID}>
                <div class="announce pm-content">{if $channel.announce}{$channel.announce|escape}{else}没有公告{/if}</div>
                <div class="pm-ctrl"><a class="pm-edit">编辑公告</a></div>
        </div>

        <div class="pm-content-border"></div>
        {include file="chan/pmail.right.ctrl.tpl"}
    </div>

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

{include file="comm/pmail.navi.tpl"}
{include file="comm/pmail.edit.tpl"}
<link type="text/css" title="style" href="css/pmail.user.css" rel="stylesheet"/>

<script type="text/JavaScript" src="js.min/chan/pmail.chan.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.chan.tip.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.chan.manage.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.chan.rank.min.js"></script>

<script type="text/JavaScript" src="js.min/core/pmail.date.min.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.mail.min.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.mail.manage.min.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.theme.min.js"></script>

</body>
</html>