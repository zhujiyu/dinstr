{include file="comm/header.comm.tpl"}
<body>
{include file="comm/top.navi.tpl"}

<div class="dis-wrap"><div class="dis-page">

    <div class="dis-page-left dis-inline-block">
        <table class="dis-layout-table dis-chan-plaza">
        {section name=i loop=$navi}<tr>
            {section name=j loop=$navi[i]}
                <td><a href="chan?tag={$navi[i][j]}">{$navi[i][j]}</a></td>
            {/section}
        </tr>{/section}
        </table>
        <div class="dis-content-border"></div>
    
        <table class="dis-layout-table">
            {section name=i loop=$themes}<tr>
                <td class="mail"><a href="mail?id={$themes[i].ID}">{$themes[i].content}</a></td>
                <td style="width: 40px;">{$themes[i].interest_num}</td>
                <td><a href="chan?id={$themes[i].channel.ID}">{$themes[i].channel.name}</a></td>
                <td style="text-align: right;">{$themes[i].update_time|date_ago:"m-d H:i"}</td>
            </tr>{/section}
        </table>
    </div><div class="dis-page-right dis-inline-block">
        {include file="modu/login.form.tpl"}

        <div class="dis-test-welcome">
            <div class="dis-module-title">关于新用户</div>
            <ul>
                <li class="dis-content">{$app.name}处于内部测试阶段，欢迎参加{$app.name}的内部测试工作！欢迎<a href="register">注册新用户</a>{*请先申请<a href="using?p=fb&view=apply">邀请码</a> {*<span class="dis-inline-block"><a class="dis-register dis-light-button" href="using?p=fb&view=apply">邀请码</a></span>*} 。</li>
                <li class="dis-content">测试中发现任何问题，或者您有任何意见和建议，请<a href="using?p=fb&view=feedback">反馈给我们</a>。</li>
            </ul>
        </div>
        <div class="dis-content-border"></div>
    </div>
    
    <div class="dis-content-border"></div>
    {include file="comm/footer.comm.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

<link type="text/css" title="style" href="css/dinstr.login.css" rel="stylesheet"/>
<script type="text/JavaScript" src="js/user/dis.user.veri.js"></script>
<script type="text/JavaScript" src="js/user/dis.login.js"></script>

</body>
</html>