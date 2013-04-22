<div class="pm-object-list ui-corner-all">
    {section name=oi loop=$objects}
        {if $objects[oi].type == 'good'}
            {include file="good/pmail.good.tpl" good=$objects[oi].good desc=$objects[oi].desc}
        {elseif $objects[oi].type == 'photo'}
        <div class="pm-photo pm-inline-block">
            <div class="pm-photo-img pm-load-display" bigimg="{$objects[oi].photo.big}" smlimg="{$objects[oi].photo.small}"><img /></div>
            <div class="pm-photo-desc">{$objects[oi].desc}(图片由<a href="user?id={$objects[oi].photo.user_id}">@{$objects[oi].photo.user.username}</a>上传)</div>
        </div>
        {/if}
        {if !$smarty.section.oi.last}<div class="pm-border-line pm-inline-block"></div>{/if}
    {/section}<div class="pm-shirt-v pm-inline-block"></div>
</div>
