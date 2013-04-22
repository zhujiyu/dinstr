{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

    <div class="pm-page-left pm-inline-block">
        <div class="pm-page-navi pm-range-navi" view="{$range}">
            <div class="pm-module-title pm-inline-block">时间线</div>
            <a class="default all item" href="home?feed">全部显示</a>
            <a class="follow item" href="home?feed&follow">可信任的</a>
        </div>

        <div class="pm-mail-list pm-feed-list" p="feed" item="flow" attr="flow">
            {section name=mli loop=$mail_list}
                {include file="mail/pmail.flow.tpl" mail=$mail_list[mli]}
                {if !$smarty.section.mli.last}<div class="pm-content-border"></div>{/if}
            {/section}
        </div>
    </div><div class="pm-page-right pm-inline-block">
        {include file="comm/pmail.right.navi.tpl" view="feed"}
    </div>

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

{include file="comm/pmail.navi.tpl"}
{include file="comm/pmail.edit.tpl"}
<script type="text/JavaScript" src="js.min/core/pmail.date.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.chan.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.chan.tip.min.js"></script>

<script type="text/JavaScript" src="js/mail/pmail.mail.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.mail.manage.min.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.theme.min.js"></script>
<script type="text/JavaScript" src="js.min/mail/pmail.feed.min.js"></script>
        {*<div class="pm-notice pm-notice-prompt pm-corner-all pm-box-shadow">
            <div><span class="pm-module-title">新通知</span></div>
            <div class="pm-notice-content"><div class="pm-border-line"></div></div>
            <table class="pm-layout-table"><tr>
                <td><a class="pm-close-notice pm-gray-button">知道了</a></td>
                <td class="pm-ctrl"><span id="desc">仅显示了前5条</span>&nbsp;<a href="noti">所有通知</a></td>
            </tr></table>
        </div>*}

{*
    <div class="pm-page-aidl pm-inline-block">
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
            <li class="collect item"><a href="ls?collect">发件夹</a></li>
            <li class="collect item"><a href="ls?collect">草稿夹</a></li>
            </div>
        </ul>
    </div><div class="pm-page-shirt pm-inline-block">
    </div><div class="pm-page-main pm-inline-block">
        <div class="pm-page-navi pm-range-navi">
            <div class="pm-module-title pm-inline-block">时间线</div>
            <a class="default all item" href="home?feed">全部显示</a>
            <a class="follow item" href="home?feed&follow">可信任的</a>
        </div>

        <div class="pm-mail-list pm-feed-list" p="feed" item="flow" attr="flow">
            {section name=mli loop=$mail_list}
                {include file="mail/pmail.flow.tpl" mail=$mail_list[mli]}
                {if !$smarty.section.mli.last}<div class="pm-content-border"></div>{/if}
            {/section}
        </div>
    </div><div class="pm-page-shirt pm-inline-block">
    </div><div class="pm-page-aidr pm-inline-block">
        {if !$charged}
            {include file="comm/pmail.money.ctrl.tpl"}
        {/if}
        <div class="pm-user-param pm-param">
            <a class="pm-inline-block" href="user?id={$user.ID}&view=join"><span class="data">{$user.param.join_num}</span>加入频道</a>
            <span class="pm-vertical-border pm-inline-block"></span>
            <a class="pm-inline-block" href="user?id={$user.ID}&view=imoney"><span class="data pm-imoney">{$user.param.imoney}</span>天鹅金币</a>
            <span class="pm-vertical-border pm-inline-block"></span>
            <a class="pm-inline-block" href="user?id={$user.ID}&view=mail"><span class="data">{$user.param.mail_num}</span>发布资讯</a>
        </div>
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
    </div>
*}
</body>
</html>