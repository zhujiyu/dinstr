{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

    <div class="pm-page-left pm-inline-block">
        <div class="pm-module-title">发送新邮件</div>
        <div class="pm-publish-mail"></div>
    </div><div class="pm-page-right pm-inline-block">
        <div class="pm-user-param pm-param">
            <a class="pm-inline-block" href="user?id={$user.ID}&view=mail"><span class="data">{$user.param.mail_num}</span>发布邮件</a>
            <span class="pm-vertical-border pm-inline-block"></span>
            <a class="pm-inline-block" href="user?id={$user.ID}&view=join"><span class="data">{$user.param.join_num}</span>加入频道</a>
            <span class="pm-vertical-border pm-inline-block"></span>
            <a class="pm-inline-block" href="user?id={$user.ID}&view=imoney"><span class="data pm-imoney">{$user.param.imoney}</span>天鹅金币</a>
        </div>
        <div class="pm-content-border"></div>
    </div>

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div><!-- end of page --> </div> <!-- end of wrap -->

{include file="comm/pmail.navi.tpl"}
{include file="comm/pmail.edit.tpl"}
<script src="js/mail/pmail.publish.js" type="text/JavaScript"></script>

        {*<!--
        {if !$charged}
            {include file="comm/pmail.money.ctrl.tpl"}
            <script type="text/JavaScript" src="js.min/user/pmail.money.min.js"></script>
        {/if}
        -->*}
</body>
</html>