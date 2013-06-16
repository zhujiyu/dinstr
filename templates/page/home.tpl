{include file="comm/header.comm.tpl"}
<body>
{include file="comm/top.navi.tpl"}

<div class="dis-page dis-wide-page">
    <div class="dis-slide-menu ui-corner-all">
        {include file="modu/chan.navi.tpl"}
    </div>
    <div class="dis-info-board">
        <div class="dis-page-left dis-inline-block">
            <div class="dis-page-navi dis-range-navi" view="{$range}">
                <a class="current all item" href="home?feed">今日海报</a>
                <a class="follow item" href="home?feed&follow">一周之内</a>
                <a class="follow item" href="home?feed&follow">半月之内</a>
                <a class="follow item" href="home?feed&follow">更早时期</a>
            </div>
            <div class="dis-info-list dis-main-list">
                {section name=ili loop=$info_list}
                    {include file="objs/info.obj.tpl" info=$info_list[ili]}
                    {if !$smarty.section.ili.last}<div class="dis-content-border"></div>{/if}
                {/section}
            </div>
        </div>
        <div class="dis-page-right dis-inline-block">
            {include file="modu/chan.detail.tpl"}
        </div>
    </div>
{*
        <div class="dis-chan-info">
            {include file="modu/chan.detail.tpl"}
        </div>
            <div class="dis-page-navi dis-range-navi" view="{$range}">
                <a class="current all item" href="home?feed">今日海报</a>
                <a class="follow item" href="home?feed&follow">一周之内</a>
                <a class="follow item" href="home?feed&follow">半月之内</a>
                <a class="follow item" href="home?feed&follow">更早时期</a>
            </div>
        <div class="dis-info-list dis-main-list">
            {section name=ili loop=$info_list}
                {include file="objs/info.obj.tpl" info=$info_list[ili]}
                {if !$smarty.section.ili.last}<div class="dis-content-border"></div>{/if}
            {/section}
        </div>
    <div class="dis-page-left dis-chan-navi dis-inline-block">
        {include file="modu/chan.navi.tpl"}
    </div>
    <div class="dis-page-right dis-info-board dis-inline-block">
        <div class="dis-chan-info">
            {include file="modu/chan.detail.tpl"}
        </div>
            <div class="dis-page-navi dis-range-navi" view="{$range}">
                <a class="current all item" href="home?feed">今日海报</a>
                <a class="follow item" href="home?feed&follow">一周之内</a>
                <a class="follow item" href="home?feed&follow">半月之内</a>
                <a class="follow item" href="home?feed&follow">更早时期</a>
            </div>
        <div class="dis-info-list dis-main-list">
            {section name=ili loop=$info_list}
                {include file="objs/info.obj.tpl" info=$info_list[ili]}
                {if !$smarty.section.ili.last}<div class="dis-content-border"></div>{/if}
            {/section}
        </div>
    </div>
*}
    <div class="dis-content-border"></div>
    {include file="comm/footer.comm.tpl"}
</div> <!-- end of page -->

</body>
</html>
        {*<div class="dis-info-list">
            <div class="dis-page-navi dis-range-navi" view="{$range}">
                <a class="current all item" href="home?feed">今日海报</a>
                <a class="follow item" href="home?feed&follow">一周之内</a>
                <a class="follow item" href="home?feed&follow">半月之内</a>
                <a class="follow item" href="home?feed&follow">更早时期</a>
            </div>
        </div>*}
{*
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
*}
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
        