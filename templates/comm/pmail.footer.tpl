<div class="pm-foot">
    <table class="pm-layout-table"><tr>
        <td style="width:300px;"><a href="using?p=about">关于我们</a>&nbsp;&nbsp;<a href="using?p=agree">服务协议</a>&nbsp;&nbsp;<a href="using?p=agree">新手入门</a>&nbsp;&nbsp;<a href="using?p=agree">加入我们</a></td>
        <td style="text-align:center;" class="pm-logo">{$app.goal}&nbsp;<span class="name">{$app.name}</span><div class="pm-logo-img pm-inline-block"><img src="{$app.logo}"></div></td>
        <td style="width:300px; text-align: right;">{$app.icp}&nbsp;{$comp.copyright}</td>
    </tr></table>
</div>

<input type="hidden" name="userid" id="userid" value="{$user.ID}">
<input type="hidden" name="username" id="username" value="{$user.username}">
<input type="hidden" name="smallavatar" id="smallavatar" value="{$user.avatar.small}">
<input type="hidden" name="bigavatar" id="bigavatar" value="{$user.avatar.big}">