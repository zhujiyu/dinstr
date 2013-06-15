{include file="comm/header.comm.tpl"}
<body>
{include file="comm/top.navi.tpl"}

<div class="dis-wrap"><div class="dis-page">
    <div class="dis-page-left dis-info dis-info-full dis-inline-block">
        <div class="dis-info-title"><a href="info?{$info.head.ID}" target="_blank">{$info.head.content}</a></div>
        <div class="dis-info-user"> <a href="user?{$info.user.ID}" target="_blank">{$info.user.username}——{$info.user.sign}</a></div>
        {if $info.objects}
            {section name=oli loop=$info.objects}
                {if $info.objects[oli].type == 'photo'}
                    <div class="dis-load-display dis-info-photo dis-img" imgsrc={$info.objects[oli].photo.big}></div>
                {/if}
            {/section}
        {/if}
        <div class="dis-info-detial">
            {$info.content}
        </div>
    </div>
    <div class="dis-page-right dis-inline-block">
        {include file="objs/user.simple.tpl"}
    </div>
    <div class="dis-content-border"></div>
    {include file="comm/footer.comm.tpl"}
</div><!-- end of page --> </div> <!-- end of wrap -->

</body>
</html>
{*
        <div class="dis-info dis-info" id="{$info.ID}">
            <div class="dis-info-title"><a href="info?{$info.head.ID}" target="_blank">{$info.head.content}</a></div>
            <div class="dis-info-user"> <a href="user?{$info.user.ID}" target="_blank">{$info.user.username}——{$info.user.sign}</a></div>
            {if $info.objects && $info.objects[0].type == 'photo'}
                <div class="dis-load-display dis-info-photo dis-img" imgsrc={$info.objects[0].photo.big}></div>
            {/if}
            <div class="dis-info-detial">{$info.content}</div>
        </div>
        *}
