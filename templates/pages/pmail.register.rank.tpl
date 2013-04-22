{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

    <div class="pm-reg-navi">
        <span class="text"><span class="symbol">1</span><span>订阅频道</span>
        </span><span class="arrow arrow-curr"><span class="tear"></span><span class="head"></span>
        </span><span class="text current"><span class="symbol">2</span><span>频道排序</span>
        </span><span class="arrow"><span class="tear"></span><span class="head"></span>
        </span><span class="text"><span class="symbol">3</span><span>完善资料</span>
        </span><span class="arrow"><span class="tear"></span><span class="head"></span>
        </span><span class="text"><span class="symbol">4</span><span>发布消息</span></span>
    </div>
    <div class="pm-content-border"></div>

    <div class="pm-page-left pm-inline-block">
        <ul class="pm-channel-list">
        {section name=cli loop=$channels}<li>
            {include file="chan/pmail.channel.tpl" channel=$channels[cli]}
            {if !$smarty.section.cli.last}<div class="pm-content-border"></div>{/if}</li>
        {/section}
        </ul>
    </div><div class="pm-page-right pm-inline-block">
    </div>
    <div class="pm-content-border"></div>
    <div class="pm-regi-ctrl pm-ctrl"><a class="pm-light-button" href="regi?publish">下一步</a></div>

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

{include file="comm/pmail.navi.tpl"}
<link type="text/css" title="style" href="css/pmail.page.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.register.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.channel.css" rel="stylesheet"/>
<script type="text/JavaScript" src="js.min/chan/pmail.channel.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.channel.rank.min.js"></script>

</body>
</html>