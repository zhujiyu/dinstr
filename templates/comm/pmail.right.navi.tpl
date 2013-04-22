<div class="pm-user-param pm-param">
    <a class="pm-inline-block" href="user?id={$user.ID}&view=join"><span class="data">{$user.param.join_num}</span>加入频道</a>
    <span class="pm-vertical-border pm-inline-block"></span>
    <a class="pm-inline-block" href="user?id={$user.ID}&view=imoney"><span class="data pm-imoney">{$user.param.imoney}</span>天鹅金币</a>
    <span class="pm-vertical-border pm-inline-block"></span>
    <a class="pm-inline-block" href="user?id={$user.ID}&view=mail"><span class="data">{$user.param.mail_num}</span>发布资讯</a>
</div>
<div class="pm-content-border"></div>

<ul class="pm-content-navi" view="{$view}">
    <div>
    <div class="pm-module-title">收件...</div>
    <li class="feed item"><a href="home?feed">最新邮件</a></li>
    <li class="reply item"><a href="home?reply">回复我的</a></li>
    <li class="interest item"><a href="ls?interest">关注邮件</a></li>
    <li class="approve item"><a href="ls?approve">赞同邮件</a></li>
    </div>
    <div>
    <div class="pm-module-title">重点...</div>
    <li class="1d item"><a href="home?important&period=1d">一天之内</a></li>
    <li class="3d item"><a href="home?important&period=3d">三天之内</a></li>
    <li class="week item"><a href="home?important&period=week">一周之内</a></li>
    <li class="month item"><a href="home?important&period=month">一月之内</a></li>
    </div>
    <div>
    <div class="pm-module-title">频道...</div>
    <li class="subscribe item"><a href="ls?channel">订阅频道({$user.param.subscribe_num})</a></li>
    <li class="join item"><a href="ls?channel">加入频道({$user.param.join_num})</a></li>
    <li class="plaza item"><a href="chan?plaza">频道广场</a></li>
    </div>
    <div>
    <div class="pm-module-title">好友...</div>
    <li class="follow item"><a href="ls?friend&follow">我信任的({$user.param.follow_num})</a></li>
    <li class="fans item"><a href="ls?friend&fans">信任我的({$user.param.fans_num})</a></li>
    <li class="msg item"><a href="msg">我的私信({$user.param.msg_num})</a></li>
    </div>
    <div>
    <div class="pm-module-title">管理...</div>
    <li class="collect item"><a href="ls?collect">收藏夹</a></li>
    <li class="publish item"><a href="ls?publish">发件夹</a></li>
    <li class="draft   item"><a href="ls?collect">草稿夹</a></li>
    </div>
</ul>
<div class="pm-content-border"></div>

<div class="pm-module-title">常用操作</div>
<div class="pm-content">
    <span class="pm-inline-block"><span class="pm-icon ui-icon-person"></span></span>
    <a href="using?p=invite">邀请好友加入{$app.name}</a>
</div>
<div class="pm-content">
    <span class="pm-inline-block"><span class="pm-icon ui-icon-signal-diag"></span></span>
    <a href="chan?create">申请开通新频道</a>
</div>
<div class="pm-content-border"></div>

<div class="pm-module-title">使用指南</div>
<div class="pm-content">
    <ul>
        <li class="pm-content"><a>如何发新邮件？</a></li>
        <li class="pm-content"><a>怎么获取邮件币？</a></li>
        <li class="pm-content"><a>如何加入频道？</a></li>
    </ul>
</div>
<div class="pm-content-border"></div>

<div class="pm-module-title">意见建议</div>
<div class="pm-idea">
    <div class="pm-content">欢迎给{$app.name}提出宝贵的意见或建议，我们会认真处理。<ul><li><a href="using?p=fb&view=feedback">在线提交</a></li><li>推送到{$app.name}开发者频道。</li></ul></div>
</div>

{*<li class="6h item"><a href="home?important&period=6h">六小时内</a></li>*}
{*
<ul class="pm-content-navi" view="{$view}">
    <li class="feed item"><a href="home?feed">最新邮件</a></li>
    <li class="important item"><a href="home?important">重要邮件</a></li>
    <li class="reply item"><a href="home?reply">回复我的</a></li>
    <div class="pm-content-border"></div>
    <li class="interest item"><a href="ls?interest">关注主题</a></li>
    <li class="approve item"><a href="ls?approve">参与主题</a></li>
    <li class="collect item"><a href="ls?collect">我的收藏</a></li>
    <div class="pm-content-border"></div>
    <li class="publish item"><a href="ls?publish">邮件({$user.param.mail_num})</a></li>
    <li class="channel item"><a href="ls?channel">频道({$user.param.join_num}/{$user.param.subscribe_num})</a></li>
    <li class="friend item"><a href="ls?friend">好友({$user.param.follow_num}/{$user.param.fans_num})</a></li>
</ul>
<div class="pm-content-border"></div>
*}
