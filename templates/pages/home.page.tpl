{include file="comm/header.comm.tpl"}
<body>
{include file="comm/top.navi.tpl"}

<div class="dis-wrap"><div class="dis-page">
    {include file="comm/page.head.tpl"}
    {if $err}
        <div class="dis-content-border"></div>
        <div class="dis-err">{$err}</div>
    {/if}
    <div class="dis-content-border"></div>

    <div class="dis-page-left dis-inline-block">
        {include file="modu/chan.navi.tpl"}
    </div><div class="dis-page-right dis-inline-block">
        <div class="dis-info-list dis-inline-block">
            {section name=ili loop=$info_list}
                {include file="objs/info.obj.tpl" info=$info_list[ili]}
                {if !$smarty.section.ili.last}<div class="dis-content-border"></div>{/if}
            {/section}
        </div><div class="dis-chan-info dis-inline-block">
            {include file="modu/chan.detail.tpl"}
        </div>
    </div>

    <div class="dis-content-border"></div>
    {include file="comm/footer.comm.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

<link type="text/css" title="style" href="css/dinstr.page.css" rel="stylesheet"/>
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

        {*
        <form class="dis-search-form dis-border dis-corner-all" method="get" action="search">
            <input type="text" name="keyword" id="keyword" value="搜频道" class="dis-no-border">
            </input><input type="submit" id="search" value=""></input>
        </form>
        {section name=cli loop=$chan_list}
            {include file="objs/navi.chan.obj.tpl" chan=$chan_list[cli]}
            {if !$smarty.section.cli.last}<div class="dis-border-line"></div>{/if}
        {/section}
        *}
</body>
</html>