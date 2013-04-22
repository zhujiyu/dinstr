<div class="pm-user-simple" id="{$user.ID}">
    <div class="pm-user-avatar pm-inline-block">
        <div class="pm-avatar-img pm-load-display pm-avatar-middle" imgsrc="{$user.avatar.small}" title="{$user.username}">
            <a class="pm-user-link" href="user?id={$user.ID}"></a>
        </div>
    </div><div class="pm-user-info pm-inline-block">
        <span class="pm-user-name"><a href="user?id={$user.ID}">{$user.username}</a></span>
        <div class="pm-user-param">
            订阅 <a href="user?id={$user.ID}&subscribe">{$user.param.subscribe_num}</a>&nbsp;
            粉丝 <a href="user?id={$user.ID}&fans">{$user.param.fans_num}</a>&nbsp;
            邮件 <a href="user?id={$user.ID}&mail">{$user.param.mail_num}</a>
        </div>
    </div>
        <div class="pm-user-intro">{$user.self_intro|escape}</div>
    <div class="pm-user-ctrl pm-ctrl" id="{$user.ID}">
        {if $user.ID != $logUser.ID}
            {if $user.followed}<a class="pm-follow-cancel">取消信任</a>{else}<a class="pm-follow">信任他</a>{/if}
        {/if}
    </div>
</div>
