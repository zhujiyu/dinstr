{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-page pm-login-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

    <div class="pm-page-left pm-inline-block">
        <table class="pm-layout-table pm-channel-plaza">
        {section name=i loop=$navi}<tr>
            {section name=j loop=$navi[i]}
                <td><a href="chan?tag={$navi[i][j]}">{$navi[i][j]}</a></td>
            {/section}
        </tr>{/section}
        </table>
        <div class="pm-content-border"></div>
    
        <table class="pm-layout-table">
            {section name=i loop=$themes}<tr>
                <td class="mail"><a href="mail?id={$themes[i].ID}">{$themes[i].content}</a></td>
                <td style="width: 40px;">{$themes[i].interest_num}</td>
                <td><a href="chan?id={$themes[i].channel.ID}">{$themes[i].channel.name}</a></td>
                <td style="text-align: right;">{$themes[i].update_time|date_ago:"m-d H:i"}</td>
            </tr>{/section}
        </table>
    </div><div class="pm-page-right pm-inline-block">
        {include file="user/pmail.user.login.tpl"}
{*
        <div class="pm-user-login" style="padding: 20px">
            {include file="user/pmail.login.form.tpl"}
            <div class="pm-content-border"></div>
            <div style="text-align:center"><a href="regi" class="pm-light-button">注册新用户</a></div>
        </div>
        <div class="pm-content-border"></div>
*}
        <div class="pm-test-welcome">
            <div class="pm-module-title">关于新用户</div>
            <ul>
                <li class="pm-content">{$app.name}处于内部测试阶段，欢迎参加{$app.name}的内部测试工作！欢迎<a href="register">注册新用户</a>{*请先申请<a href="using?p=fb&view=apply">邀请码</a> {*<span class="pm-inline-block"><a class="pm-register pm-light-button" href="using?p=fb&view=apply">邀请码</a></span>*} 。</li>
                <li class="pm-content">测试中发现任何问题，或者您有任何意见和建议，请<a href="using?p=fb&view=feedback">反馈给我们</a>。</li>
            </ul>
        </div>
        <div class="pm-content-border"></div>
    </div>
    
    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div> </div>

{include file="comm/pmail.guest.top.tpl"}

</body>
</html>