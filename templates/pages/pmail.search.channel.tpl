{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

        <div class="so-page-left so-inline-block" id="main">

        </div><div class="so-page-right so-inline-block so-corner-all" id="addition">

        </div>

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

{include file="comm/pmail.navi.tpl"}
<link type="text/css" title="style" href="css/pmail.page.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.news.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.office.css" rel="stylesheet"/>

</body>
</html>