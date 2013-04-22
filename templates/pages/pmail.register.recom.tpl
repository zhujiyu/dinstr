{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

    <div class="pm-reg-navi">
        <span class="text current"><span class="symbol">1</span><span>订阅频道</span>
        </span><span class="arrow"><span class="tear"></span><span class="head"></span>
        </span><span class="text"><span class="symbol">2</span><span>频道排序</span>
        </span><span class="arrow"><span class="tear"></span><span class="head"></span>
        </span><span class="text"><span class="symbol">3</span><span>完善资料</span>
        </span><span class="arrow"><span class="tear"></span><span class="head"></span>
        </span><span class="text"><span class="symbol">4</span><span>发布消息</span></span>
    </div>
    <div class="pm-content-border"></div>

    <div class="pm-channel-navi pm-inline-block">
        {include file="chan/pmail.channel.navi.tpl"}
    </div><div class="pm-card-list pm-inline-block">
        {section name=i loop=$channels max=8}
            {include file="chan/pmail.channel.card.tpl" channel=$channels[i]}
            {if $smarty.section.i.index % 2 == 0}<div class="pm-shirt-v pm-inline-block"></div>{else}<div class="pm-shirt-h"></div>{/if}
        {/section}
    </div>
    <div class="pm-content-border"></div>
    <div class="pm-regi-ctrl pm-ctrl"><a class="pm-light-button" href="regi?rank">下一步</a></div>

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

{include file="comm/pmail.navi.tpl"}
<link type="text/css" title="style" href="css/pmail.page.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.register.css" rel="stylesheet"/>
<script type="text/JavaScript" src="js.min/chan/pmail.channel.min.js"></script>

</body>
</html>