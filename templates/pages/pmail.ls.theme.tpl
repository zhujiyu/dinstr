{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

    <div class="pm-page-left pm-inline-block">
        <div class="pm-module-title">{$title}</div>

        <div class="pm-theme-list pm-more-list" item="{$view}">
            {section name=mli loop=$theme_list}
                <div class="pm-content-border"></div>
                {include file="mail/pmail.theme.detail.tpl" mail=$theme_list[mli].mail theme=$theme_list[mli]}
            {/section}
        </div>
    </div><div class="pm-page-right pm-inline-block">
            {include file="comm/pmail.right.navi.tpl" view=$item}
    </div> <!-- end of page -->
    
    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

{include file="comm/pmail.navi.tpl"}
<link type="text/css" title="style" href="css/pmail.page.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.mail.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.channel.css" rel="stylesheet"/>

<script type="text/JavaScript" src="js.min/core/pmail.photo.min.js"></script>
<script type="text/JavaScript" src="js.min/core/pmail.date.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.channel.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.chan.tip.min.js"></script>

<script type="text/JavaScript" src="js.min/mail/pmail.mail.edit.min.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.mail.manage.min.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.mail.min.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.theme.min.js"></script>

</body>
</html>