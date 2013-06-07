<div class="dis-page-ctrl">
    {if $chan.status}
        <span class="dis-light-button">订阅</span>
    {else}
        <div class="dis-light-button dis-publish">
            <span class="dis-icon-wrap"><span class="dis-icon ui-icon-pencil"></span></span>
            &nbsp;贴海报
        </div>
    {/if}
</div>
<div class="dis-content-border"></div>

<div class="dis-chan">
    <div class="dis-inline-block dis-avatar-middle dis-tile-img dis-load-display"
         imgsrc="{$chan.logo.small}"></div>
    <div class="dis-inline-block">
        <div class="dis-module-title">{$chan.name}</div>
        {if $chan.type == 'business'}商务{elseif $chan.type == 'info'}资讯{else}社交{/if}类
        <span>{$chan.subscriber_num}</span>订阅 <span>{$chan.member_num}</span>会员
    </div>
    <div class="dis-chan-desc dis-content">{$chan.desc}</div>
</div>
<div class="dis-content-border"></div>

<div class="dis-member-list dis-user-list">
    <div class="dis-module-title">
        <a>查看全部...</a>活跃会员
    </div>
    {section name=mli loop=$member_list}
        <div class="dis-user">
            <div class="dis-user-avatar dis-inline-block">
                <div class="dis-avatar-small dis-load-display" imgsrc={$member_list[mli].avatar.small}></div>
            </div><div class="dis-user-param dis-inline-block">
                {$member_list[mli].username}
                <br>
                {$member_list[mli].sign}
            </div>
        </div>
        {*if !$smarty.section.mli.last}<div class="dis-border-line"></div>{/if*}
    {/section}
</div>
<div class="dis-content-border"></div>

<div class="dis-manage-list dis-user-list">
    <div class="dis-module-title">
        <a>查看全部...</a>管理员
    </div>
    {section name=mli loop=$manager_list}
        <div class="dis-user">
            <div class="dis-user-avatar dis-inline-block">
                <div class="dis-avatar-small dis-load-display" imgsrc={$manager_list[mli].avatar.small}></div>
            </div><div class="dis-user-param dis-inline-block">
                {$manager_list[mli].username}
                <br>
                {$manager_list[mli].sign}
            </div>
        </div>
    {/section}
</div>
{*<div class="dis-content-border"></div>*}

        {*if !$smarty.section.mli.last}<div class="dis-border-line"></div>{/if*}
    {*<div class="dis-list-ctrl">
        <div class="dis-module-title dis-inline-block">会员</div>
        <div class="dis-inline-block"><a>查看全部...</a></div>
    </div>*}
    {*<div class="dis-list-ctrl">
        <div class="dis-module-title dis-inline-block">管理员</div>
        <div class="dis-ctrl dis-inline-block"><a>查看全部...</a></div>
    </div>*}
    