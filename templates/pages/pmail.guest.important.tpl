{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}
    {include file="chan/pmail.chan.manage.tpl"}

    <div class="pm-page-left pm-inline-block">
        <div class="pm-module-title pm-inline-block">价值线</div>

        <div class="pm-mail-list pm-more-list" item="important" extname="period" extvalue="{$period}">
            {section name=mli loop=$mail_list}
                {include file="mail/pmail.flow.tpl" mail=$mail_list[mli]}
                {if !$smarty.section.mli.last}<div class="pm-content-border"></div>{/if}
            {/section}
        </div>
    </div><div class="pm-page-right pm-inline-block">
        {include file="comm/pmail.guest.right.tpl" view=$period}
    </div>

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

{include file="comm/pmail.guest.top.tpl"}
<link type="text/css" title="style" href="css/pmail.page.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.mail.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.chan.css" rel="stylesheet"/>

<script type="text/JavaScript" src="js.min/jquery/jquery.ui.sortable.min.js"></script>
<script type="text/JavaScript" src="js.min/core/pmail.date.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.chan.guest.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.chan.tip.min.js"></script>

<script type="text/JavaScript" src="js.min/mail/pmail.good.min.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.mail.min.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.mail.manage.min.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.theme.min.js"></script>

        {*<!--
            <ul class="pm-page-navi" view={$period}>
                <div class="pm-module-title pm-inline-block">价值线</div>
                <li class="6h all item"><a href="guest?important&period=6h">6小时</a></li>
                <li class="1d item"><a href="guest?important&period=1d">一天</a></li>
                <li class="3d item"><a href="guest?important&period=3d">三天</a></li>
                <li class="week item"><a href="guest?important&period=week">一周</a></li>
                <li class="month item"><a href="guest?important&period=month">一月</a></li>
            </ul>
        -->*}
</body>
</html>