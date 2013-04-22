{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

    <div class="pm-page-left pm-inline-block">
            {*<div class="pm-page-navi" view="{$item}">
                <div class="pm-module-title">{$title}</div>
                <a class="subscribe item" href="ls?channel&subscribe">订阅的频道({$user.param.subscribe_num})</a>
                <a class="default join item" href="ls?channel&join">加入的频道({$user.param.join_num})</a>
            </div>*}

        <div class="pm-channel-list">
            {section name=cli loop=$channel_list}
                {include file="chan/pmail.channel.tpl" channel=$channel_list[cli]}
            {/section}
        </div>
    </div><div class="pm-page-right pm-inline-block">
        {include file="comm/pmail.right.navi.tpl" view=$item}
            {*include file="comm/pmail.right.navi.tpl" view="channel"*}
    </div>

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

{include file="comm/pmail.navi.tpl"}
<link type="text/css" title="style" href="css/pmail.page.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.mail.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.chan.css" rel="stylesheet"/>

<script type="text/JavaScript" src="js.min/jquery/jquery.ui.sortable.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.chan.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.chan.rank.min.js"></script>

</body>
</html>