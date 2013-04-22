{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

    <div class="pm-page-left pm-inline-block">
        <div class="pm-module-title">{$title}</div>
        <div class="pm-mail-list pm-more-list" item="publish">
            {section name=mli loop=$mail_list}
                <div class="pm-content-border"></div>
                {include file="mail/pmail.mail.tpl" mail=$mail_list[mli]}
            {/section}
        </div>
    </div><div class="pm-page-right pm-inline-block">
        {include file="comm/pmail.right.navi.tpl" view="publish"}
    </div> <!-- end of page-right -->

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

{include file="comm/pmail.navi.tpl"}
{include file="comm/pmail.edit.tpl"}

<script type="text/JavaScript" src="js.min/core/pmail.date.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.chan.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.chan.tip.min.js"></script>

<script type="text/JavaScript" src="js.min/mail/pmail.mail.manage.min.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.mail.min.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.theme.min.js"></script>

</body>
</html>