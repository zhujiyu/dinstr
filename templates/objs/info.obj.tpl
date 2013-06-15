<div class="dis-info dis-info-brief" id="{$info.ID}">
    <div class="dis-info-title"><a href="info?{$info.head.ID}" target="_blank">{$info.head.content}</a></div>
    <div class="dis-info-user"> <a href="user?{$info.user.ID}" target="_blank">{$info.user.username}——{$info.user.sign}</a></div>
    {if $info.objects && $info.objects[0].type == 'photo'}
        <div class="dis-load-display dis-info-photo dis-img" imgsrc={$info.objects[0].photo.big}></div>
    {/if}
    <div class="dis-info-detial">{$info.content|truncate_utf:110:'...'}<a>查看详细</a></div>
</div>
    {*
    <div class="dis-info-user">{$info.user.username}</div>
    {if $info.objects}
        {if $info.objects[0].type == 'photo'}
            <div class="dis-load-display dis-info-photo dis-tile-img" imgsrc={$info.objects[0].photo.small}></div>
        {/if}
    {/if}
    {if $info.objects}
        <div class="dis-info-objs">
        {if $info.objects[0].type == 'photo'}
        <div class="dis-load-display dis-info-photo dis-tile-img" 
             imgsrc={$info.objects[0].photo.small}></div>
        {elseif $info.objects[0].type == 'good'}
        {/if}
        </div>
    {/if}
    <div class="dis-info-body">
        <div class="dis-info-title"><a>{$info.head.content}</a></div>
        <div class="dis-info-user">{$info.user.username}</div>
        <div class="dis-info-detial">{$info.content}</div>
    </div>
    *}
    {*<div class="dis-info-objs dis-inline-block">
    {if $info.objects}
        {if $info.objects[0].type == 'photo'}
        <div class="dis-load-display dis-info-photo" imgsrc={$info.objects[0].photo.small}></div>
        {<div class="dis-load-display" imgstr={$info.objects[0].photo.small}></div>}
        {else}
        {/if}
    {/if}
    </div>*}
    