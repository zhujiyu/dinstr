{include file="comm/header.comm.tpl"}
<body>
{include file="comm/top.navi.tpl"}

<div class="dis-page dis-thin-page">
    <div class="dis-slide-menu ui-corner-all">
        {include file="modu/chan.navi.tpl"}
    </div>
    <div class="dis-info-board">
        <div class="dis-page-left dis-info-full dis-inline-block">
            <a href="info?{$info.head.ID}" target="_blank"><h2>{$info.head.content}</h2></a>
            <div class="dis-info-user"> <a href="user?{$info.user.ID}" target="_blank">{$info.user.username}——{$info.user.sign}</a></div>
            {if $info.objects}
                {section name=oli loop=$info.objects}
                    {if $info.objects[oli].type == 'photo'}
                        <div class="dis-load-display dis-info-photo dis-img" imgsrc={$info.objects[oli].photo.big}></div>
                    {/if}
                {/section}
            {/if}
            <div class="dis-info-detial">
                {$info.content}
            </div>
        </div>
        <div class="dis-page-right dis-inline-block">
            {include file="objs/user.simple.tpl"}
            <div class="dis-module-title">最近发布的信息</div>
            <div class="dis-info-list dis-small-list">
                {section name=ili loop=$info_list}
                    {include file="objs/info.obj.tpl" info=$info_list[ili]}
                    {if !$smarty.section.ili.last}<div class="dis-content-border"></div>{/if}
                {/section}
            </div>
        </div>
    </div>
    
    <div class="dis-content-border"></div>
    {include file="comm/footer.comm.tpl"}
</div><!-- end of page -->

</body>
</html>
    {*<div class="dis-page-left dis-info dis-info-full dis-inline-block">
        <a href="info?{$info.head.ID}" target="_blank"><h2>{$info.head.content}</h2></a>
        <div class="dis-info-user"> <a href="user?{$info.user.ID}" target="_blank">{$info.user.username}——{$info.user.sign}</a></div>
        {if $info.objects}
            {section name=oli loop=$info.objects}
                {if $info.objects[oli].type == 'photo'}
                    <div class="dis-load-display dis-info-photo dis-img" imgsrc={$info.objects[oli].photo.big}></div>
                {/if}
            {/section}
        {/if}
        <div class="dis-info-detial">
            {$info.content}
        </div>
    </div>
    <div class="dis-page-right dis-inline-block">
        {include file="objs/user.simple.tpl"}
        <div class="dis-module-title">最近发布的信息</div>
        <div class="dis-content-border"></div>
        <div class="dis-info-list dis-small-list">
            {section name=ili loop=$info_list}
                {include file="objs/info.obj.tpl" info=$info_list[ili]}
                {if !$smarty.section.ili.last}<div class="dis-content-border"></div>{/if}
            {/section}
        </div>
    </div>*}
        {*<div class="dis-user" id="{$user.ID}">
            <div class="dis-user-avatar dis-inline-block">
                <div class="dis-load-display dis-avatar-img dis-avatar-big" imgsrc="{$user.avatar.big}" title="{$user.username}">
                    <a href="user?id={$user.ID}"></a>
                </div>
            </div><div class="dis-user-info dis-inline-block">
                <div class="dis-user-name"><a href="user?id={$user.ID}">{$user.username}</a></div>
                <div class="dis-user-sign">{$user.sign}</div>
            </div>
            <div class="dis-user-intro">{$user.self_intro|escape}</div>
            <div class="dis-user-ctrl dis-ctrl" id="{$user.ID}">
                {if $user.ID != $logUser.ID}
                    {if $user.followed}<a class="dis-follow-cancel">取消信任</a>{else}<a class="dis-follow">信任他</a>{/if}
                {/if}
            </div>
        </div>*}
{*
        <div class="dis-info-title"><a href="info?{$info.head.ID}" target="_blank">{$info.head.content}</a></div>
        <div class="dis-info dis-info" id="{$info.ID}">
            <div class="dis-info-title"><a href="info?{$info.head.ID}" target="_blank">{$info.head.content}</a></div>
            <div class="dis-info-user"> <a href="user?{$info.user.ID}" target="_blank">{$info.user.username}——{$info.user.sign}</a></div>
            {if $info.objects && $info.objects[0].type == 'photo'}
                <div class="dis-load-display dis-info-photo dis-img" imgsrc={$info.objects[0].photo.big}></div>
            {/if}
            <div class="dis-info-detial">{$info.content}</div>
        </div>
        *}
