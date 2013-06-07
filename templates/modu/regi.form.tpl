<div class="dis-user-register" title="注册新用户">
    {if $introUser}
        <div class="dis-intr-user">
            <div>你的好友<a href="user?id={$introUser.ID}">{$introUser.username}</a>邀请你一起加入{$app.name}</div>
            <br>
            <div class="dis-user-avatar dis-inline-block">
                <div class="dis-avatar-img dis-load-display dis-avatar-middle" 
                     imgsrc="{$introUser.avatar.small}" title="{$introUser.username}">
                    <a class="dis-user-link" href="user?id={$introUser.ID}"></a>
                </div>
            </div><div class="user-info dis-inline-block">
                <a href="user?id={$introUser.ID}">{$introUser.username}</a> {$introUser.sign}<br>
                {$introUser.self_intro}
                {*if $introUser.live_city}<span class="dis-icon-wrap dis-inline-block"><span class="dis-icon ui-icon-person"></span></span>{$introUser.live_city}<br>{/if*}
            </div>
        </div>
    {/if}
    
    <form class="dis-register-form" action="regi" method="post">
        <input type="hidden" name="p" value="regi"/>
        <input type="hidden" name="invite" value="{$invite.ID}"/>
        <input type="hidden" name="intr" value="{$introUser.ID}"/>
        
        <div class="dis-module-title">注册新用户</div>
        <table class="dis-layout-table">
            <tr class="email dis-veri-data">
                <td class="label"><label for="email">邮&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;箱：</label></td>
                <td class="data-td"><input class="dis-login-input" type="text" name="email" id="email" value="{$email}" {if $email}readonly="true"{/if}></input></td>
                <td class="desc-td"><span class="dis-veri"><span class="dis-icon ui-icon-check"></span></span>
                <label class="dis-desc"></label></td>
            </tr>
            <tr class="pword dis-veri-data">
                <td><label for="pword">密&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;码：</label></td>
                <td class="data-td"><input class="dis-login-input" type="password" name="pword" id="pword"></input></td>
                <td class="desc-td"><span class="dis-veri"><span class="dis-icon ui-icon-check"></span></span><label class="dis-desc"></label></td>
            </tr>
            <tr class="pword dis-veri-data">
                <td><label for="pword2">确认密码：</label></td>
                <td class="data-td"><input class="dis-login-input" type="password" name="pword2" id="pword2"></input></td>
                <td class="desc-td"><span class="dis-veri"><span class="dis-icon ui-icon-check"></span></span><label class="dis-desc"></label></td>
            </tr>
            <tr class="uname dis-veri-data">
                <td><label for="uname">昵&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;称：</label></td>
                <td class="data-td"><input class="dis-login-input" type="text" name="uname" id="uname"></input></td>
                <td class="desc-td"><span class="dis-veri"><span class="dis-icon ui-icon-check"></span></span>
                <label class="dis-desc"></label></td>
            </tr>
        </table>
        <div class="dis-ctrl">
                {if $err}
                    <div class="note ui-state-error">{$err}！</div>
                {/if}
                <a class="dis-gray-button" id="reset">重置</a>&nbsp;&nbsp;
                <a class="dis-light-button" id="register">注册</a>
                <input class="" type="reset" value="重置"/>
                <input class="" type="submit" value="注册"/>
        </div>
    </form>
</div>

            {*<!-- <tr class="gender">
                <td><label>性&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;别：</label></td>
                <td><input type="radio" name="gender" value="none" id="gender1" checked>
                    <label for="gender1">不想说</label></input>
                <input type="radio" name="gender" value="female" id="gender3">
                    <label for="gender3">女士</label></input>
                <input type="radio" name="gender" value="male" id="gender2">
                    <label for="gender2">先生</label></input></td><td></td>
            </tr> -->*}
            {*<!-- <div class="option">
                <input type="checkbox" value="agree" checked id="pact1"></input>
                <label for="pact1">同意购吧乐</label><a href="">用户协议</a>
            </div> -->*}
        {*<table class="dis-layout-table"><tr><td class="dis-user-avatar">
            {include file="user/pmail.user.avatar.tpl" user=$introUser type="dis-avatar-middle"}
        </td><td class="user-info">
            <a href="user?id={$introUser.ID}">{$introUser.username}</a><br>
            {include file="user/pmail.user.param.tpl" param=$introUser.param}
            {if $introUser.live_city}<span class="dis-icon-wrap dis-inline-block"><span class="dis-icon ui-icon-person"></span></span>{$introUser.live_city}<br>{/if}
            {$introUser.self_intro}
        </td></tr></table>*}
        