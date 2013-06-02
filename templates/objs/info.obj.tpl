<div class="dis-info dis-info-brief" id="{$info.ID}">
    <div class="dis-info-objs dis-inline-block">
    {if $info.objects}
        {if $info.objects[0].type == 'photo'}
        <div class="dis-load-display dis-info-photo dis-tile-img" imgsrc={$info.objects[0].photo.small}></div>
        {*<div class="dis-load-display" imgstr={$info.objects[0].photo.small}></div>*}
        {else}
        {/if}
    {/if}
    </div><div class="dis-info-body dis-inline-block">
        <div class="dis-info-title"><a>{$info.head.content}</a></div>
        <div class="dis-info-user">{$info.user.username}</div>
        <div class="dis-info-detial">{$info.content}</div>
    </div>
</div>
