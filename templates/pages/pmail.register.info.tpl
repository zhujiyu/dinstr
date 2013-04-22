{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

    <div class="pm-reg-navi">
        <span class="text"><span class="symbol">1</span><span>订阅频道</span>
        </span><span class="arrow"><span class="tear"></span><span class="head"></span>
        </span><span class="text"><span class="symbol">2</span><span>频道排序</span>
        </span><span class="arrow arrow-curr"><span class="tear"></span><span class="head"></span>
        </span><span class="text current"><span class="symbol">3</span><span>完善资料</span>
        </span><span class="arrow"><span class="tear"></span><span class="head"></span>
        </span><span class="text"><span class="symbol">4</span><span>发布消息</span></span>
    </div>
    <div class="pm-content-border"></div>

    <div class="pm-user-edit pm-object-edit" id="{$user.ID}">
        <div class="pm-edit-info">
            <div class="pm-module-title">基本信息</div>
            {include file="user/pmail.edit.info.tpl"}
        </div>
        <div class="pm-edit-extend">
            <div class="pm-module-title">选填信息</div>
            {include file="user/pmail.edit.extend.tpl"}
        </div>
        <div class="pm-content-border"></div>
        <div class="pm-regi-ctrl pm-ctrl"><a class="pm-light-button save" href="regi?tag">下一步</a></div>
{*
        {include file="user/pmail.edit.info.tpl"}
        <div class="pm-content-border"></div>
        {include file="user/pmail.edit.extend.tpl"}
        <div class="pm-content-border"></div>
*}
    </div>

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

{include file="comm/pmail.navi.tpl"}
<link type="text/css" title="style" href="css/pmail.page.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.user.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.register.css" rel="stylesheet"/>

<script type="text/JavaScript" src="js.min/core/pmail.photo.min.js"></script>
<script type="text/JavaScript" src="js.min/user/pmail.user.veri.min.js"></script>
<script type="text/JavaScript" src="js.min/user/pmail.user.setting.min.js"></script>

</body>
</html>