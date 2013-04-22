<div class="pm-user pm-object-item" id="{$user.ID}">
    <div class="pm-user-avatar pm-object-avatar pm-inline-block">
        {include file="user/pmail.user.avatar.tpl" type="pm-avatar-middle"}
    </div><div class="pm-user-info pm-object-info pm-inline-block">
        {include file="user/pmail.user.info.tpl"}
    </div><div class="pm-ctrl pm-user-ctrl pm-inline-block" id="{$user.ID}">
        {if $user.ID != $logUser.ID}
            {if $user.followed}<a class="pm-follow-cancel">取消信任</a>{else}<a class="pm-follow">信任他</a>{/if}
        {/if}
    </div>
</div>