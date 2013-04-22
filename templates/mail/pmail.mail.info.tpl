<div class="pm-mail-content pm-mail-content-small">
    {if $mail.objects}
    <div class="pm-object-list">
    {section name=oi loop=$mail.objects}
        {if $mail.objects[oi].type == 'good'}
        <div class="pm-good pm-good-small pm-inline-block" id="{$mail.objects[oi].good.ID}" source="{$mail.objects[oi].good.source}">
            <div class="pm-good-photo pm-load-display" imgsrc="{$mail.objects[oi].good.pic_url}"></div>
            <div class="pm-good-desc">{$mail.objects[oi].desc}(商品由<a href="user?id={$mail.objects[oi].good.user_id}">@{$mail.objects[oi].good.user.username}</a>上传)</div>
        </div>
        {elseif $mail.objects[oi].type == 'photo'}
        <div class="pm-photo pm-inline-block">
            <div class="pm-photo-img pm-load-display" bigimg="{$mail.objects[oi].photo.big}" smlimg="{$mail.objects[oi].photo.small}"></div>
            <div class="pm-photo-desc">{$mail.objects[oi].desc}(图片由<a href="user?id={$mail.objects[oi].photo.user_id}">@{$mail.objects[oi].photo.user.username}</a>上传)</div>
        </div>
        {/if}
    {/section}<div class="pm-shirt-v pm-inline-block"></div>
    </div>
    {/if}
    <span class="content">{$mail.content|truncate_utf:150:'...'}</span>
    {if $mail.content|@strlen > 300}<a class="pm-expan"></a>{/if}
{*    <div class="pm-text-content">
        <span class="content">{$mail.content|truncate_utf:150:'...'}</span>
        {if $mail.content|@strlen > 300}
            <a class="pm-expan">显示全部<span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-arrowthickstop-1-s"></span></span></a>
        {/if}
    </div> *}
</div>