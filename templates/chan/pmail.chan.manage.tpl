<div class="pm-channel-manage">
    <div class="pm-left-list pm-inline-block">
        <div class="pm-module-title">推荐频道</div>
        <div class="pm-content-border"></div>
        <div class="pm-logo-list">
            {section loop=$channels name=ci}<div 
                class="pm-inline-block pm-channel-link pm-channel" id="{$channels[ci].ID}">
                    <div class="logo pm-avatar-img pm-avatar-middle pm-load-display" imgsrc="{$channels[ci].logo.big}"></div>
                    <div class="name">{$channels[ci].name|truncate_utf:6:'...'}</div>
            </div>{/section}
        </div>
    </div>
    <div class="pm-right-list pm-inline-block">
        <div class="pm-module-title">已订阅频道</div>
    <div class="pm-content-border"></div>
        <div class="pm-channel-list">{section loop=$subscribers name=si max=4}
        <div class="pm-channel" id="{$subscribers[si].ID}">
            <div class="pm-avatar-img pm-avatar-middle pm-channel-logo pm-load-display pm-inline-block" 
                 imgsrc="{$subscribers[si].logo.big}"></div>
            <div class="pm-channel-info pm-inline-block">
                <div><strong><a href="chan?id={$subscribers[si].ID}">{$subscribers[si].name}</a></strong></div>
                <div>成员：{$subscribers[si].member_num}&nbsp;订阅：{$subscribers[si].subscriber_num}</div>
            </div>
        </div>
        {/section}</div>
    </div>
    <div class="pm-content-border"></div>
</div>
