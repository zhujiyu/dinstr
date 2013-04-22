{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

    <div class="pm-page-left pm-inline-block">
            {include file="chan/pmail.chan.list.tpl" title="你可以推荐他加入以下频道："}
            <div class="pm-content-border"></div>
            
            <div class="pm-page-navi" view={$view}>
                <a class="default item email" href="using?p=invite&view=email">通过邮件</a>
                <a class="item other" href="using?p=invite&view=other">其他方式</a>
            </div>

            {if $invite_succeed}<div class="pm-empty-content">邀请发送成功！</div>{/if}
            {if $err}<div class="pm-err"><strong><span class="pm-red pm-inline-block"><span
                class="pm-icon ui-icon-info"></span></span>错误：</strong>{$err}</div>{/if}

            {if $view == 'email'}
            <form class="pm-invite-email pm-plugin" style="padding:20px 0" method="get" action="using">
                <input type="hidden" name="p" value="invite"></input>
                <div class="pm-border pm-inline-block">
                    <input class="pm-no-border" type="text" name="email" id="email"
                        value="{$email}" style="width:400px;padding:5px;"></input>
                </div>
                <div class="pm-ctrl pm-inline-block">
                    <input class="" type="submit" value="发送邀请"></input>
                    <a id="ok" class="pm-light-button" style="padding:5px 10px;">发送邀请</a>
                </div>
            </form>
            {else}
            <div class="pm-invite-other" style="padding:20px 0">
                {$app.url}/register?invi={$invite_code}
            </div>
            {/if}

            <div class="pm-content-border"></div>
            <div class="pm-module-title">邀请的好友</div>
            <div class="pm-user-list">
                {section name=i loop=$invites}
                    {include file="user/pmail.user.invite.tpl" user=$invites[i].invite_user
                        logUser=$user email=$invites[i].email}
                    {if !$smarty.section.i.last}<div class="pm-border-line"></div>{/if}
                {sectionelse}
                <div class="pm-empty-content">你还没有邀请好友</div>
                {/section}
            </div>
    </div><div class="pm-page-right pm-inline-block">
            <div class="pm-content-navi" view="{$view}">
                <a class="feed item" href="index?view=feed">最新动态</a>
                <a class="follow item" href="index?view=follow">关注话题</a>
                <a class="reply item" href="index?view=reply">回复评论</a>
                <a class="favorite item" href="index?view=favorite">我的收藏</a>
            </div>
            <div class="pm-content-border"></div>
            {include file="comm/pmail.idea.tpl"}
    </div>
    
    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div><!-- end of page --> </div> <!-- end of wrap -->

{if $user}
    {include file="comm/pmail.navi.tpl"}
{else}
    {include file="comm/pmail.guest.top.tpl"}
{/if}

<link type="text/css" title="style" href="css/pmail.page.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.user.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.channel.css" rel="stylesheet"/>

<script type="text/JavaScript" src="js.min/user/pmail.login.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.channel.min.js"></script>

</body>
</html>
