{include file="user/pmail.user.login.tpl"}

<ul class="pm-content-navi" view="{$view}">
    <div>
    <div class="pm-module-title">收件...</div>
    <li class="feed item"><a href="guest?feed">最新邮件</a></li>
    </div>
    <div>
    <div class="pm-module-title">重点...</div>
    <li class="1d item"><a href="guest?important&period=1d">一天之内</a></li>
    <li class="3d item"><a href="guest?important&period=3d">三天之内</a></li>
    <li class="week item"><a href="guest?important&period=week">一周之内</a></li>
    <li class="month item"><a href="guest?important&period=month">一月之内</a></li>
    </div>
</ul>
<div class="pm-content-border"></div>
{*
<ul class="pm-content-navi" view="{$view}">
    <div>
    <li class="feed item"><a href="guest?feed">最新邮件</a></li>
    <li class="important item"><a href="guest?important">重要邮件</a></li>
    </div>
</ul>
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

<div class="pm-module-title">{$app.name}使用指南</div>
<div class="pm-content">
    <ul>
        <li class="pm-content"><a>如何发新邮件？</a></li>
        <li class="pm-content"><a>怎么获取邮件币？</a></li>
        <li class="pm-content"><a>如何加入频道？</a></li>
    </ul>
</div>
<div class="pm-content-border"></div>

<div class="pm-idea">
    <div class="pm-module-title">给{$app.name}提建议</div>
    <div class="pm-content">欢迎给{$app.name}提出宝贵的意见或建议，我们会认真处理。<ul><li><a href="using?p=fb&view=feedback">在线提交</a></li><li>推送到{$app.name}开发者频道。</li></ul></div>
</div>
