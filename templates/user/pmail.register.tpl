{if $step == 1}
    <div class="so-user-register ui-corner-all" title="注册新用户">
        <div class="so-header">注册新用户</div>
        {if $introUser}
        <div class="so-intr-user ui-corner-top">
            <table class="so-layout-table"><tbody><tr><td class="avatar">
                {include file="module/g86.avatar.html" avatar=$introUser.avatar
                    type="so-avatar-middle" userid=$introUser.ID}
            </td><td class="user-info">
                Hi，我是<a href="user?u={$introUser.ID}">{$introUser.username}</a>
                <br>邀请你一起加入购吧乐
            </td></tr></tbody></table>
        </div>
        {/if}
        <form action="register" method="post" class="ui-corner-all">
            <input type="hidden" name="p" value="register"/>
            <input type="hidden" name="step" value="1"/>
            <input type="hidden" name="intr" value="{$introUser.ID}"/>
            <div class="email" veri="0">
                <label>邮&nbsp;&nbsp;&nbsp;&nbsp;箱：</label>
                <input class="so-login-input" type="text" name="email" id="email"></input>
                <span class="so-veri so-inline-block"><img src="css/images/ui-icons_eb4f01_256x240.png"/></span>
                <br><label class="desc"></label>
            </div>
            <div class="pword" veri="0">
                <label>密&nbsp;&nbsp;&nbsp;&nbsp;码：</label>
                <input class="so-login-input" type="password" name="pword" id="pword"></input>
                <span class="so-veri so-inline-block"><img src="css/images/ui-icons_eb4f01_256x240.png"/></span>
                <br><label class="desc"></label>
            </div>
            <div class="uname" veri="0">
                <label>昵&nbsp;&nbsp;&nbsp;&nbsp;称：</label>
                <input class="so-login-input" type="text" name="uname" id="uname"></input>
                <span class="so-veri so-inline-block"><img src="css/images/ui-icons_eb4f01_256x240.png"/></span>
                <br><label class="desc"></label>
            </div>
            <div class="gender">
                <label>性&nbsp;&nbsp;&nbsp;&nbsp;别：</label>
                {*<input type="radio" name="gender" value="none" checked id="gender1">
                    <label for="gender1">保留</label></input>*}
                <input type="radio" name="gender" value="female" id="gender3" checked>
                    <label for="gender3">女士</label></input>
                <input type="radio" name="gender" value="male" id="gender2">
                    <label for="gender2">先生</label></input>
            </div>
            <div class="option">
                <input type="checkbox" value="agree" checked id="pact1">
                <label for="pact1">同意购吧乐</label></input><a href="">用户协议</a>
            </div>
            <div class="ctrl">
                {if $err}
                    <div class="note ui-state-error">{$err}！</div>
                {/if}
                <input class="so-button" type="reset" value="重置"/>
                <input class="so-button" type="submit" value="注册"/>
            </div>
        </form>
    </div>
{elseif $step == 2}
    <div class="so-module-title"><h2>帐号激活成功！</h2></div>
    <form method="post" action="register">
        <input type="hidden" name="step" value="3"></input>
        <div>关注一些感兴趣的人，你将会获得更多的乐趣！</div>
        <table class="so-layout-table so-rec-users"><tbody>
            {section name=i loop=$recUsers}
            {if $smarty.section.i.index % 4 == 0}<tr>{/if}
            <td>
                {include file="module/g86.rec.user.html" user=$recUsers[i]}
                <input type="checkbox" name="trust[]" id="id{$smarty.section.i.index}" value="{$recUsers[i].ID}" checked>
                    <label for="id{$smarty.section.i.index}">{$recUsers[i].username}</label>
                </input>
            </td>
            {if $smarty.section.i.index % 4 == 3 || $smarty.section.i.last}</tr>{/if}
            {/section}
        </tbody></table>
        <div class="ctrl"><input type="submit" value="关注已选用户"></input></div>
    </form>
{elseif $success}
    <div style="margin: 0 auto; width: 500px;">
        <h2>帐号注册成功！马上激活邮件，完成注册！</h2>
        <p>激活链接已经发送你的邮箱：{$email}</p>
        <p>{$success}</p>
    </div>
{/if}
{*
{else}
    <div class="so-user-register ui-corner-all" title="注册新用户">
        <div class="so-header">注册新用户</div>

        {if $introUser}
        <div class="so-intr-user ui-corner-top">
            <table class="so-layout-table"><tbody><tr><td class="avatar">
                {include file="module/g86.avatar.html" avatar=$introUser.avatar
                    type="so-avatar-middle" userid=$introUser.ID}
            </td><td class="user-info">
                Hi，我是<a href="user?u={$introUser.ID}">{$introUser.username}</a>
                <br>邀请你一起加入购吧乐
            </td></tr></tbody></table>
        </div>
        {/if}
        
        <form action="login" method="post" class="ui-corner-all">
            <input type="hidden" name="p" value="register"/>
            <input type="hidden" name="step" value="1"/>
            <input type="hidden" name="intr" value="{$introUser.ID}"/>

            <input type="hidden" name="pword-veri" id="pword-veri" value="0"/>
            <input type="hidden" name="email-veri" id="email-veri" value="0"/>
            <input type="hidden" name="uname-veri" id="uname-veri" value="0"/>

            <div class="email" veri="0">
                <label>邮&nbsp;&nbsp;&nbsp;&nbsp;箱：</label>
                <input class="so-login-input" type="text" name="email" id="email"></input>
                <span class="so-veri so-inline-block"><img src="css/images/ui-icons_eb4f01_256x240.png"/></span>
                <br><label class="desc"></label>
            </div>
            <div class="pword" veri="0">
                <label>密&nbsp;&nbsp;&nbsp;&nbsp;码：</label>
                <input class="so-login-input" type="password" name="pword" id="pword"></input>
                <span class="so-veri so-inline-block"><img src="css/images/ui-icons_eb4f01_256x240.png"/></span>
                <br><label class="desc"></label>
            </div>
            <div class="uname" veri="0">
                <label>昵&nbsp;&nbsp;&nbsp;&nbsp;称：</label>
                <input class="so-login-input" type="text" name="uname" id="uname"></input>
                <span class="so-veri so-inline-block"><img src="css/images/ui-icons_eb4f01_256x240.png"/></span>
                <br><label class="desc"></label>
            </div>
            <div class="gender">
                <label>性&nbsp;&nbsp;&nbsp;&nbsp;别：</label>
                {*<input type="radio" name="gender" value="none" checked id="gender1">
                    <label for="gender1">保留</label></input>*}
                <input type="radio" name="gender" value="female" id="gender3" checked>
                    <label for="gender3">女士</label></input>
                <input type="radio" name="gender" value="male" id="gender2">
                    <label for="gender2">先生</label></input>
            </div>

            <table class="so-layout-table"><tbody>
            <tr class="email">
                <td class="name">邮&nbsp;&nbsp;&nbsp;&nbsp;箱：</td>
                <td><input class="so-login-input" type="text" name="email" id="email"></input></td>
                <td>{include file="module/g86.input.note.html"
                    initNote="输入常用邮箱，这是你找回密码的唯一方式" errNote="该邮箱已经注册！"}</td>
            </tr>
            <tr class="password">
                <td class="name">密&nbsp;&nbsp;&nbsp;&nbsp;码：</td>
                <td><input class="so-login-input" type="password" name="password" id="password"></input></td>
                <td>{include file="module/g86.input.note.html"
                    initNote="至少6位的数字/英文/特殊符号" errNote="密码格式错误！"}</td>
            </tr>
            <tr class="username">
                <td class="name">昵&nbsp;&nbsp;&nbsp;&nbsp;称：</td>
                <td><input class="so-login-input" type="text" name="username" id="username"></input></td>
                <td>{include file="module/g86.input.note.html"
                    initNote="用户名是你在购吧的显示名，建议使用真名，方便你的朋友找到你"
                    errNote="该用户名已经被他人占用了！"}</td>
            </tr>
            <tr class="gender">
                <td class="name">性&nbsp;&nbsp;&nbsp;&nbsp;别：</td>
                <td id="gender">
                    <input type="radio" name="gender" value="none" checked id="gender1">
                        <label for="gender1">保留</label></input>
                    <input type="radio" name="gender" value="male" id="gender2">
                        <label for="gender2">先生</label></input>
                    <input type="radio" name="gender" value="female" id="gender3">
                        <label for="gender3">女士</label></input></td>
                <td>{include file="module/g86.input.note.html"
                    initNote="输入性别，帮助购吧乐提供人性化服务" errNote=""}</td>
            </tr>
            <tr class="confirmpwd">
                <td class="name">确认密码：</td>
                <td><input class="so-login-input" type="password" name="confirmpwd" id="confirmpwd"></input></td>
                <td>{include file="module/g86.input.note.html"
                    initNote="重复账户密码" errNote="两次输入密码不同！"}</td>
            </tr>
            <tr>
                <td class="name">居住城市：</td><td><input type="text" name="city"></input></td>
                <td>{include file="module/g86.input.note.html"
                    initNote="输入居住城市，帮助你进行同城交流" errNote=""}</td>
            </tr>
            <tr>
                <td class="name">验&nbsp;&nbsp;证&nbsp;&nbsp;码：</td>
                <td><input type="text" name="authnum" id="authnum"></input></td>
                <td class="auth">
                    <div style="float: left;">
                        <img src="include/api/g86.authnum.png.php" style="vertical-align: middle;"/>
                        <a href="" class="change-authnum">换一张</a>
                    </div>
                    {include file="module/g86.input.note.html" initNote="" errNote="验证码错误！"}
                </td>
            </tr>
            </tbody></table>

            <div class="option">
                <input type="checkbox" value="agree" checked id="pact1">
                <label for="pact1">同意购吧乐</label></input><a href="">用户协议</a>
            </div>
            <div class="ctrl">
                {if $err}
                    <div class="note ui-state-error">{$err}！</div>
                {/if}
                <input class="so-button" type="reset" value="重置"/>
                <input class="so-button" type="submit" value="注册"/>
            </div>
        </form>
    </div>
{/if}
*}
