<div class="pm-channel-global" id="{$channel.ID}">
    <div class="pm-inline-block pm-channel-logo">
        <div class="pm-avatar-img pm-avatar-big pm-tile-img pm-load-display"
            imgsrc="{$channel.logo.big}"><img /></div>
    </div><div class="pm-channel-info pm-inline-block">
        <div class="pm-channel-name">
            <span class="name">{$channel.name}</span>
            {include file="chan/pmail.channel.type.tpl"}
        </div>
        <a href="">{$app.url}/chan/{if $channel.domain}{$channel.domain}{else}{$channel.ID}{/if}</a>
        <div>{$channel.description|escape}</div>
    </div>
</div>