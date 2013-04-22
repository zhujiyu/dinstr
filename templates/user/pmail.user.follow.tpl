<div class="pm-user-ctrl pm-page-ctrl" id="{$target_user.ID}">
    {if !$user || $target_user.ID != $user.ID}
        {if $relation.followed}
            {if $relation.following}
                <span class="pm-gray-button"><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-transferthick-e-w"></span></span>&nbsp;<span>相互关注</span>&nbsp;|&nbsp;<a class="pm-follow-cancel">取消</a></span>
            {else}
                <span class="pm-follow pm-light-button"><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-arrowthick-1-e"></span></span>&nbsp;关注</span>
            {/if}
        {else}
            {if $relation.following}
                <span class="pm-gray-button"><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-arrowthick-1-w"></span></span>已关注&nbsp;|&nbsp;<a class="pm-follow-cancel">取消</a></span>
            {else}
                <span class="pm-follow pm-light-button"><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-plusthick"></span></span>&nbsp;关注</span>
            {/if}
        {/if}
        <span class="pm-gray-button pm-deny"><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-star"></span></span><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-triangle-1-s"></span></span></span>
    {else}
        <span class="pm-follow pm-gray-button" href="user?edit"><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-wrench"></span></span><span>编辑我的资料</span></span>
    {/if}
</div>
