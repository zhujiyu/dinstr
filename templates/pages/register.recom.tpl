{include file="comm/header.comm.tpl"}
<body>
{include file="comm/top.navi.tpl"}

<div class="dis-wrap"><div class="dis-page">
    <div class="dis-reg-navi">
        <span class="text current"><span class="symbol">1</span><span>订阅频道</span>
        </span><span class="arrow"><span class="tear"></span><span class="head"></span>
        </span><span class="text"><span class="symbol">2</span><span>完善资料</span>
        </span><span class="arrow"><span class="tear"></span><span class="head"></span>
        </span><span class="text"><span class="symbol">3</span><span>发布海报</span></span>
    </div>
    <div class="dis-content-border"></div>

    <div class="dis-channel-navi dis-inline-block">
        {include file="modu/chan.tags.tpl"}
    </div><div class="dis-card-list dis-inline-block">
        {section name=i loop=$channels max=8}
            {include file="objs/chan.card.tpl" chan=$channels[i]}
            {if $smarty.section.i.index % 2 == 0}<div class="dis-shirt-v dis-inline-block"></div>{else}<div class="dis-shirt-h"></div>{/if}
        {/section}
    </div>
    <div class="dis-content-border"></div>
    <div class="dis-regi-ctrl dis-ctrl"><a class="dis-light-button" href="regi?rank">下一步</a></div>

    <div class="dis-content-border"></div>
    {include file="comm/footer.comm.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

<link type="text/css" title="style" href="css/dinstr.login.css" rel="stylesheet"/>
<script type="text/JavaScript" src="js.min/chan/pmail.channel.min.js"></script>

</body>
</html>