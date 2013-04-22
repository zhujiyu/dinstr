{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-error">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}
    
    <div class="pm-shirt-h"></div>
    <div class="pm-register">
            <div class="pm-inline-block">
                {include file="user/pmail.register.form.tpl" err=''}
            </div><div class="pm-shirt-v pm-inline-block">
            </div><div class="pm-user-login pm-inline-block">
                已有帐号，请<a href="login">直接登录</a>
                {include file="user/pmail.login.form.tpl" err=''}
            </div>
    </div>
    <div class="pm-shirt-h"></div>

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

{include file="comm/pmail.guest.top.tpl"}
<link type="text/css" title="style" href="css/pmail.register.css" rel="stylesheet"/>
<script type="text/JavaScript" src="js.min/user/pmail.user.veri.min.js"></script>

</body>
</html>