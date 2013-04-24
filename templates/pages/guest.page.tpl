{include file="comm/header.comm.tpl"}
<body>
{include file="comm/guest.top.comm.tpl"}
<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

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

</body>
</html>