<div class="pm-user-login">
    <form class="pm-login-form" action="login" method="post">
        <input type="hidden" name="p" value="login"/>
        <input class="pm-login-input" type="text" name="uname" id="uname" value="邮箱/用户名/用户ID"/>
        <input class="pm-login-input" type="text" name="pword-prompt" id="pword-prompt" value="密码"/>
        <input class="pm-login-input" type="password" name="pword" id="pword" value=""/>
        <span style="display:inline-block;vertical-align: middle;">
            <input type="checkbox" name="autologin" checked id="autologin"><label for="autologin">自动登录</label></input>&nbsp;&nbsp;
            <a href="login?p=pword">忘记密码</a>
        </span>
        <span class="pm-login-button"><a class="pm-login-submit pm-light-button">登录</a></span>
        <input type="submit" style="display: none" value="登录"></input>
    </form>
    <div class="pm-content-border"></div>
    <div style="text-align:center; padding-bottom: 20px;"><a href="regi" class="pm-light-button">注册新用户</a></div>
</div>
<div class="pm-content-border"></div>
