{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

    <div class="pm-page-left pm-inline-block">
            <div class="pm-page-navi pm-range-navi" view="{$range}">
                <div class="pm-module-title pm-inline-block">收到回复</div>
                <a class="default all item" href="home?feed">全部显示</a>
                <a class="follow item" href="home?feed&follow">可信任的</a>
            </div>

            <div class="pm-mail-list pm-more-list" item="reply">
                {section name=mli loop=$mail_list}
                    {include file="mail/pmail.mail.tpl" mail=$mail_list[mli]}
                    {if !$smarty.section.mli.last}<div class="pm-content-border"></div>{/if}
                {/section}
            </div>
    </div><div class="pm-page-right pm-inline-block">
            {if !$charged}{include file="comm/pmail.money.ctrl.tpl"}{/if}
            {include file="comm/pmail.right.navi.tpl" view="reply"}
    </div>

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

{include file="comm/pmail.navi.tpl"}
{include file="comm/pmail.edit.tpl"}
<script type="text/JavaScript" src="js.min/core/pmail.date.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.chan.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.chan.tip.min.js"></script>

<script type="text/JavaScript" src="js.min/mail/pmail.mail.min.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.mail.manage.min.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.theme.min.js"></script>

</body>
</html>