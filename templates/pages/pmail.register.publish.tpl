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
        </span><span class="arrow"><span class="tear"></span><span class="head"></span>
        </span><span class="text"><span class="symbol">3</span><span>完善资料</span>
        </span><span class="arrow arrow-curr"><span class="tear"></span><span class="head"></span>
        </span><span class="text current"><span class="symbol">4</span><span>发布消息</span></span>
    </div>
    <div class="pm-content-border"></div>

    <div class="pm-page-left pm-inline-block">
        <div class="pm-publish-mail">
            <div class="pm-edit-title pm-module-title">打个招呼吧</div>
        </div>
    </div><div class="pm-page-right pm-inline-block">
        {include file="user/pmail.user.param.tpl"}
        <div class="pm-content-border"></div>
        {include file="comm/pmail.money.ctrl.tpl"}
    </div>
    <div class="pm-content-border"></div>
    <div class="pm-regi-ctrl pm-ctrl"><a class="pm-light-button" href="home?important">下一步</a></div>

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

{include file="comm/pmail.navi.tpl"}
<link type="text/css" title="style" href="css/pmail.page.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.mail.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.register.css" rel="stylesheet"/>

<script type="text/JavaScript" src="js.min/core/pmail.photo.min.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.mail.edit.min.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.mail.min.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.publish.min.js"></script>

</body>
</html>