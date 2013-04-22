{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}
    {*<a href="index">{include file="comm/pmail.logo.tpl"}</a>*}

    <div style="min-height: 400px; text-align:center; padding:20px;">
        <div class="pm-module-title"><h2>ERR404：{$title}</h2></div>
        <div class="pm-desc-item"><h3>{$404}</h3></div>
    </div>
    <div class="pm-content-border"></div>
    
    {include file="comm/pmail.footer.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

{include file="comm/pmail.guest.top.tpl"}
</body>
</html>