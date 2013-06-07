{include file="comm/header.comm.tpl"}
<body>
{include file="comm/top.navi.tpl"}

<div class="dis-wrap"><div class="dis-page">
    <div class="dis-register">
        <div class="dis-inline-block">
            {include file="modu/regi.form.tpl" err=''}
        </div><div class="dis-shirt-v dis-inline-block">
        </div><div class="dis-user-login dis-inline-block">
            已有帐号，请<a href="login">直接登录</a>
            {include file="modu/login.form.tpl" err=''}
        </div>
    </div>
    <div class="dis-content-border"></div>
    {include file="comm/footer.comm.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

<link type="text/css" title="style" href="css/dinstr.login.css" rel="stylesheet"/>
<script type="text/JavaScript" src="js/user/dis.user.veri.js"></script>
<script type="text/JavaScript" src="js/user/dis.login.js"></script>

</body>
</html>