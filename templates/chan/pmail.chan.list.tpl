<div class="pm-logo-list pm-inline-block">
    <div class="pm-module-title">{$title}</div>
    {section loop=$channels name=ci}<div class="pm-inline-block pm-channel-link" id="{$channels[ci].ID}">
        <div class="pm-avatar-img pm-avatar-middle pm-channel-logo pm-load-display" imgsrc="{$channels[ci].logo.big}"></div>
    </div>{/section}
</div>
