{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

    <div class="pm-page-left pm-inline-block">
        <div class="pm-module-title">{$title}</div>
{*
            <div class="pm-page-navi" view="{$item}">
                <div class="pm-module-title">{$title}</div>
                <a class="default fans item" href="ls?friend&fans">信任我的人({$user.param.subscribe_num})</a>
                <a class="follow item" href="ls?friend&follow">我信任的人({$user.param.join_num})</a>
            </div>
*}
        <div class="pm-user-list pm-object-list">
            {section name=uli loop=$friend_list}
                <div class="pm-content-border"></div>
                {include file="user/pmail.user.tpl" user=$friend_list[uli] logUser=$user}
                {*if !$smarty.section.uli.last}<div class="pm-content-border"></div>{/if*}
            {/section}
        </div>
    </div><div class="pm-page-right pm-inline-block">
        {include file="comm/pmail.right.navi.tpl" view=$item}
    </div>

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

{include file="comm/pmail.navi.tpl"}
<link type="text/css" title="style" href="css/pmail.page.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.user.css" rel="stylesheet"/>
<script type="text/JavaScript" src="js.min/user/pmail.user.min.js"></script>
<script type="text/JavaScript" src="js.min/user/pmail.message.min.js"></script>

</body>
</html>