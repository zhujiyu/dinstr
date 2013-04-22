{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-page pm-plaza-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

    <div class="pm-page-left pm-inline-block">
    </div><div class="pm-page-right pm-inline-block">
        <div class="pm-content-border"></div>
        {include file="chan/pmail.right.ctrl.tpl"}
    </div>

    <div class="pm-channel-plaza">
        <div class="pm-channel-navi pm-inline-block">
            {include file="chan/pmail.channel.navi.tpl"}
        </div><div class="pm-channel-list pm-inline-block">
        {section name=i loop=$channels max=10}
            {include file="chan/pmail.channel.tpl" channel=$channels[i]}
            {if !$smarty.section.i.last}<div class="pm-content-border"></div>{/if}
        {/section}
        </div><div class="pm-avatar-plaza pm-inline-block">
        {section name=ci loop=$channels}<div class="pm-channel-link pm-inline-block" id={$channels[ci].ID}>
            {include file="chan/pmail.channel.avatar.tpl" type="pm-avatar-middle" channel=$channels[ci]}
        </div>{/section}
        </div>
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
<link type="text/css" title="style" href="css/pmail.chan.css" rel="stylesheet"/>

<script type="text/JavaScript" src="js/pmail.test.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.chan.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.chan.tip.min.js"></script>
{*
        <div class="pm-chan-plaza">
        {section name=ti loop=$top_channels max=3}
            {include file="chan/pmail.channel.card.tpl" channel=$top_channels[ti]}
            {if !$smarty.section.ti.last}<div class="pm-shirt-v pm-inline-block"></div>{/if}
        {/section}
        </div>
        <div class="pm-content-border"></div>

        <div class="pm-page-aidl pm-inline-block">
            {include file="chan/pmail.channel.navi.tpl"}
        </div><div class="pm-page-shirt pm-inline-block">
        </div><div class="pm-page-main pm-inline-block">
        {section name=i loop=$channels max=10}
            {include file="chan/pmail.channel.tpl" channel=$channels[i]}
            {if !$smarty.section.i.last}<div class="pm-content-border"></div>{/if}
        {/section}
        </div><div class="pm-page-shirt pm-inline-block">
        </div><div class="pm-page-aidr pm-inline-block">
        {section name=ci loop=$channels}<div class="pm-channel-link pm-inline-block" id={$channels[ci].ID}>
            {include file="chan/pmail.channel.avatar.tpl" type="pm-avatar-middle" channel=$channels[ci]}
        </div>{/section}
        </div>
*}
</body>
</html>