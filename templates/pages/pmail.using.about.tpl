{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-prompt-page pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

    <div class="pm-page-left pm-inline-block">
        <h2>天鹅镇——信息流引擎！</h2>

        <h3>一、开天辟地，天鹅镇诞生的背景时代</h3>
        <div class="pm-content">随着数字技术的发展，我们进入了一个信息爆炸的世界，在这个世界中，各种各样的信息就像空气一样充斥在每个人身边。我们也就像需要空气一样，一刻都离不开信息。然而，如此重要的信息世界，却是一个杂乱无章，毫无组织的世界。我们需要的信息，不知道会出现在哪里，我们不需要的信息，每天却又可能像刮起一阵飓风一样，毫无征兆地就可能把我们给裹挟进去，卷入一场毫无意义的信息旋窝之中。我们迫切需要一个井井有条的运转有序的信息世界。</div>
        <div class="pm-content">信息世界出现的第一个伟大工具是搜索引擎。搜索引擎试图用检索的方式，帮助我们从海量的杂乱的信息海洋中，找到我们需要的信息。搜索很好地完成这个任务。然而，我们对信息的需求，却远远不是只能检索信息这么简单。我们需要实时地把信息发送给正确的目标群体，我们需要随时可以看到真正和我们密切相关，关乎我们切身利益的重要信息，我们需要随时掌握信息世界的温度、湿度、风力等天气指标，在信息飓风到来之前收到预警，我们需要……</div>
        <div class="pm-content">为了满足人们对一个更加易用更加有序更加健康的完美信息世界的需求，天鹅镇诞生了！</div>

        <h3>二、天鹅镇的目标：重建易用有序的信息世界</h3>
        <div class="pm-content">天鹅镇希望砸烂一个浩瀚如海杂乱无章的信息旧世界，建设一个井井有条收发自如可控可读的新世界。</div>
        <div class="pm-content">目前天鹅镇专注于提供定向信息流服务，将每一条信息从特定的信息源，发送到特定的接受目标人群。它将成为信息世界里，除提供信息检索服务的搜索引擎之外，又一款重要的信息引擎。所谓信息流引擎，和搜索不同，天鹅镇是</div>
        <div class="pm-content">信息产业无疑是当前最有前途的行业。在这个行业里，将诞生一批巨无霸公司，就像谷歌、百度、FACEBOOK等已经做到的那样。这些公司，已经为信息世界提供了一种卓有成效的搜索引擎，一张近似无限的人和信息的网络，每天给信息世界贡献着无数。</div>

        <div class="pm-content-border"></div>
        <h3>联系我们</h3>
        <div class="pm-desc-item">
            email: zhujiyu@139.com, sardinebone@gmail.com
        </div>
        <div class="pm-module-title"><a href="index">返回首页</a></div>
    </div><div class="pm-page-right pm-inline-block">
        <h3>文件列表</h3>
        <ul>
            <li><a href="about?desc">天鹅镇简介</a></li>
            <li><a href="about?join">诚聘英才</a></li>
        </ul>
    </div>

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

{if $user}
    {include file="comm/pmail.navi.tpl"}
{else}
    {include file="comm/pmail.guest.top.tpl"}
{/if}
<link type="text/css" title="style" href="css/pmail.page.css" rel="stylesheet"/>
</body>
</html>