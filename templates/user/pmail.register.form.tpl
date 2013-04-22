<div class="pm-user-register" title="注册新用户">
    {if $introUser}<div class="pm-intr-user">
        你的好友<a href="user?id={$introUser.ID}">{$introUser.username}</a>邀请你一起加入{$app.name}
        <table class="pm-layout-table"><tr><td class="pm-user-avatar">
            {include file="user/pmail.user.avatar.tpl" user=$introUser type="pm-avatar-middle"}
        </td><td class="user-info">
            <a href="user?id={$introUser.ID}">{$introUser.username}</a><br>
            {*include file="user/pmail.user.param.tpl" param=$introUser.param*}
            {if $introUser.live_city}<span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-person"></span></span>{$introUser.live_city}<br>{/if}
            {$introUser.self_intro}
        </td></tr></table>
    </div>{/if}
    <div class="pm-module-title">注册新用户</div>
    <form action="regi" method="post" class="pm-register-form">
        <input type="hidden" name="p" value="register"/>
        <input type="hidden" name="invite" value="{$invite.ID}"/>
        <input type="hidden" name="intr" value="{$introUser.ID}"/>
        <table class="pm-layout-table">
            <tr class="email pm-veri-data">
                <td class="label"><label for="email">邮&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;箱：</label></td>
                <td class="data-td"><input class="pm-login-input" type="text" name="email" id="email" value="{$email}" {if $email}readonly="true"{/if}></input></td>
                <td class="desc-td"><span class="pm-veri"><span class="pm-icon ui-icon-check"></span></span>
                <label class="pm-desc"></label></td>
            </tr>
            <tr class="pword pm-veri-data">
                <td><label for="pword">密&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;码：</label></td>
                <td class="data-td"><input class="pm-login-input" type="password" name="pword" id="pword"></input></td>
                <td class="desc-td"><span class="pm-veri"><span class="pm-icon ui-icon-check"></span></span><label class="pm-desc"></label></td>
            </tr>
            <tr class="pword pm-veri-data">
                <td><label for="pword2">确认密码：</label></td>
                <td class="data-td"><input class="pm-login-input" type="password" name="pword2" id="pword2"></input></td>
                <td class="desc-td"><span class="pm-veri"><span class="pm-icon ui-icon-check"></span></span><label class="pm-desc"></label></td>
            </tr>
            <tr class="uname pm-veri-data">
                <td><label for="uname">昵&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;称：</label></td>
                <td class="data-td"><input class="pm-login-input" type="text" name="uname" id="uname"></input></td>
                <td class="desc-td"><span class="pm-veri"><span class="pm-icon ui-icon-check"></span></span>
                <label class="pm-desc"></label></td>
            </tr>
            {*<!-- <tr class="gender">
                <td><label>性&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;别：</label></td>
                <td><input type="radio" name="gender" value="none" id="gender1" checked>
                    <label for="gender1">不想说</label></input>
                <input type="radio" name="gender" value="female" id="gender3">
                    <label for="gender3">女士</label></input>
                <input type="radio" name="gender" value="male" id="gender2">
                    <label for="gender2">先生</label></input></td><td></td>
            </tr> -->*}
        </table>
            {*<!-- <div class="option">
                <input type="checkbox" value="agree" checked id="pact1"></input>
                <label for="pact1">同意购吧乐</label><a href="">用户协议</a>
            </div> -->*}
        <div class="pm-ctrl">
                {if $err}
                    <div class="note ui-state-error">{$err}！</div>
                {/if}
                <a class="pm-gray-button" id="reset">重置</a>&nbsp;&nbsp;
                <a class="pm-light-button" id="register">注册</a>
                <input class="" type="reset" value="重置"/>
                <input class="" type="submit" value="注册"/>
        </div>
    </form>
</div>