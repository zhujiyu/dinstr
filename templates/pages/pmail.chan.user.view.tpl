{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"> <div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

    <div class="pm-page-left pm-channel-page pm-inline-block">
        {include file="chan/pmail.chan.global.tpl"}
        <div class="pm-content-border"></div>

        <div class="pm-page-navi" view="{$view}">
            <div class="pm-module-title pm-inline-block">频道会员</div>
            <a class="default member item" href="chan?id={$channel.ID}&view=member">频道成员</a>
            <a class="subscriber item" href="?id={$channel.ID}&view=subscriber">订阅人</a>
        </div>

        {if $view == 'member'}
            <div class="pm-user-list">
                {section name=mi loop=$members}
                <div class="pm-member pm-user" user_id="{$members[mi].ID}">
                    <div class="pm-user-avatar pm-object-avatar pm-inline-block">
                        {include file="user/pmail.user.avatar.tpl" user=$members[mi] type="pm-avatar-middle"}
                    </div>
                    <div class="pm-user-info pm-object-info pm-inline-block">
                        {include file="user/pmail.user.info.tpl" user=$members[mi]}
                    </div>
                    <div class="pm-ctrl pm-inline-block">
                        <div class="pm-channel-ctrl" id="{$channel.ID}">
                            <div>{if $members[mi].role == 'member'}普通成员{else}管理员{/if}</div>
                            {*if $role == 'editor'}{if $members[mi].role == 'member'}<a class="pm-role-editor">设为管理员</a>{else}<a class="pm-role-member">取消管理权</a>{/if}{/if*}
                        </div>
                        {if $members[mi].ID != $user.ID}
                        <div class="pm-user-ctrl" id="{$members[mi].user.ID}">
                            {if $members[mi].followed}<a class="pm-follow-cancel">取消关注</a>{else}<a class="pm-follow">关注</a>{/if}
                        </div>
                        {/if}
                    </div>
                </div>
                {if !$smarty.section.mi.last}<div class="pm-border-line"></div>{/if}
                {/section}
            </div>
        {elseif $view == 'subscriber'}
            <div class="pm-user-list">
                {section name=mi loop=$subscribers}
                <div class="pm-subscriber pm-user" user_id="{$subscribers[mi].ID}">
                    <div class="pm-user-avatar  pm-object-avatar pm-inline-block">
                        {include file="user/pmail.user.avatar.tpl" user=$subscribers[mi] type="pm-avatar-middle"}
                    </div>
                    <div class="pm-user-info pm-object-info pm-inline-block">
                        {include file="user/pmail.user.info.tpl" user=$subscribers[mi]}
                    </div>
                    {if $subscribers[mi].ID != $user.ID}
                    <div class="pm-ctrl pm-user-ctrl pm-inline-block" id="{$subscribers[mi].ID}">
                        {if $subscribers[mi].followed}<a class="pm-follow-cancel">取消关注</a>{else}<a class="pm-follow">关注</a>{/if}
                    </div>
                    {/if}
                </div>
                {if !$smarty.section.mi.last}<div class="pm-border-line"></div>{/if}
                {/section}
            </div>
        {/if}
    </div><div class="pm-page-right pm-inline-block">
        {if $status}
            {include file="chan/pmail.manage.tpl"}
            {if $status && $status.role > 0}
                <div class="pm-content-border"></div>
                {include file="chan/pmail.channel.weight.tpl"}
            {/if}
            <div class="pm-content-border"></div>
        {/if}
        {include file="chan/pmail.channel.param.tpl"}

            <div class="pm-content-border"></div>
            <div class="pm-module-title">{$channel.name}的公告</div>
            <div class="pm-channel-announce pm-channel-ctrl" id={$channel.ID}>
                <div class="announce pm-content">{if $channel.announce}{$channel.announce|escape}{else}没有公告{/if}</div>
            </div>

            <div class="pm-content-border"></div>
            {include file="chan/pmail.right.ctrl.tpl"}
    </div>

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

{if $user}
    {include file="comm/pmail.navi.tpl"}
{else}
    {include file="comm/pmail.guest.top.tpl"}
{/if}
<link type="text/css" title="style" href="css/pmail.page.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.user.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.chan.css" rel="stylesheet"/>

<script type="text/JavaScript" src="js.min/user/pmail.user.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.chan.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.chan.rank.min.js"></script>

</body>
</html>