<div class="dis-user-simple" id="{$user.ID}">
    <div class="dis-user-avatar dis-inline-block">
        <div class="dis-load-display dis-avatar-img dis-avatar-middle" imgsrc="{$user.avatar.small}" title="{$user.username}">
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
</div>
