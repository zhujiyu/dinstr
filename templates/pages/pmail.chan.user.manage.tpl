{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"> <div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

    <div class="pm-page-left pm-inline-block">
        {include file="chan/pmail.chan.global.tpl"}
        <div class="pm-content-border"></div>

        <div class="pm-page-navi" view="{$view}">
            <a class="default member item" href="chan?id={$channel.ID}&view=member">频道成员</a>
            <a class="subscriber item" href="chan?id={$channel.ID}&view=subscriber">订阅人</a>
            <a class="applicant item" href="chan?id={$channel.ID}&view=applicant">加入申请</a>
        </div>

        {if $view == 'applicant'}
            <div class="pm-user-list">
                {section name=ai loop=$applicants}
                <div class="pm-applicant pm-user" id="{$applicants[ai].ID}" user_id="{$applicants[ai].user.ID}">
                    <div class="pm-user-avatar pm-object-avatar pm-inline-block">
                        {include file="user/pmail.user.avatar.tpl" user=$applicants[ai].user type="pm-avatar-middle"}
                    </div>
                    <div class="pm-user-info pm-object-info pm-inline-block">
                        {include file="user/pmail.user.info.tpl" user=$applicants[ai].user}
                        <div class="reason">申请理由：{$applicants[ai].reason}</div>
                    </div>
                    <div class="pm-ctrl pm-channel-ctrl pm-inline-block" id="{$channel.ID}">
                        {if $applicants[ai].status == 'untreated'}
                        <a class="pm-gray-button pm-apply-refuse">拒绝</a>
                        <a class="pm-light-button pm-apply-accept">同意</a>
                        {elseif $applicants[ai].status == 'accept'}已通过{else}被拒绝{/if}
                    </div>
                </div>
                {if !$smarty.section.ai.last}<div class="pm-border-line"></div>{/if}
                {/section}
            </div>
        {elseif $view == 'member'}
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
                            <div>{if $members[mi].member.role == 1}普通成员{elseif $members[mi].member.role == 2}管理员{else}创建人{/if}</div>
                            {if $members[mi].member.role == 1}<a class="pm-role-editor">设为管理员</a>{else}<a class="pm-role-member">取消管理权</a>{/if}
                        </div>
                        {if $members[mi].ID != $user.ID}
                        <div class="pm-user-ctrl" id="{$members[mi].ID}">
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
                    <div class="pm-ctrl pm-user-ctrl pm-inline-block" id="{$subscribers[mi].ID}">
                        {if $subscribers[mi].ID != $user.ID}{if $subscribers[mi].followed}<a class="pm-follow-cancel">取消关注</a>{else}<a class="pm-follow">关注</a>{/if}{/if}
                    </div>
                </div>
                    {if !$smarty.section.mi.last}<div class="pm-border-line"></div>{/if}
                {/section}
            </div>
        {/if}
    </div><div class="pm-page-right pm-inline-block">
        <div class="pm-channel-ctrl pm-page-ctrl" id="{$channel.ID}">
            <a class="pm-gray-button pm-inline-block" href="chan?id={$channel.ID}&p=edit"><span class="pm-inline-block"><span class="pm-icon ui-icon-wrench"></span></span>&nbsp;频道设置</a>
            <a class="pm-gray-button pm-inline-block" href="chan?id={$channel.ID}&view=applicant"><span class="pm-inline-block"><span class="pm-icon ui-icon-person"></span></span>&nbsp;会员管理</a>
        </div>

        <div class="pm-content-border"></div>
        {include file="chan/pmail.channel.weight.tpl"}
        <div class="pm-content-border"></div>
        {include file="chan/pmail.channel.param.tpl"}

        <div class="pm-content-border"></div>
        <div class="pm-module-title">{$channel.name}的公告</div>
        <div class="pm-channel-announce pm-channel-ctrl" id={$channel.ID}>
            <div class="announce pm-content">{if $channel.announce}{$channel.announce|escape}{else}没有公告{/if}</div>
            <div class="pm-ctrl"><a class="pm-edit">编辑公告</a></div>
        </div>

        <div class="pm-content-border"></div>
        {include file="chan/pmail.right.ctrl.tpl"}
    </div>

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

{include file="comm/pmail.navi.tpl"}
<link type="text/css" title="style" href="css/pmail.page.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.user.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.chan.css" rel="stylesheet"/>

<script type="text/JavaScript" src="js.min/chan/pmail.chan.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.chan.rank.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.chan.manage.min.js"></script>
<script type="text/JavaScript" src="js.min/user/pmail.user.min.js"></script>

</body>
</html>