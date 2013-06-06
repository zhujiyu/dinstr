<div class="dis-member-list">
    <div class="dis-module-title">本板活跃会员</div>
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
        {if !$smarty.section.mli.last}<div class="dis-border-line"></div>{/if}
    {/section}
</div>
<div class="dis-content-border"></div>
<div class="dis-manage-list">
    <div class="dis-module-title">本板管理员</div>
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
        {if !$smarty.section.mli.last}<div class="dis-border-line"></div>{/if}
    {/section}
</div>
