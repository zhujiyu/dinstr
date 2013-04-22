{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

        <div style="margin: 0 auto; width: 500px; height: 400px; padding-top: 100px;">
            <h2>恭喜您，邮箱激活成功！</h2>
            <p>马上激活邮件，完成注册吧！</p>
            <p>激活链接已经发送你的邮箱：<span style="color:#eb4f01">{$email}</span></p>
            <p>{$success}</p>
        </div>

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

{include file="comm/pmail.navi.tpl"}
<link type="text/css" title="style" href="css/pmail.page.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.register.css" rel="stylesheet"/>
<script type="text/JavaScript" src="js.min/user/pmail.user.veri.min.js"></script>

</body>
</html>