<div class="dis-chan dis-chan-navi dis-box-shadow" id="{$chan.ID}" title="{$chan.name}">
    <div class="dis-chan-logo dis-inline-block">
        <div class="dis-avatar-middle dis-tile-img dis-load-display" imgsrc="{$chan.logo.small}"></div>
        {*<div class="dis-avatar-img dis-tile-img dis-load-display dis-avatar-middle" imgsrc="{$chan.logo.small}" title="{$chan.name}">
            <a href="chan?id={$chan.ID}"></a>
        </div>*}
    </div><div class="dis-chan-param dis-inline-block">
        <div class="dis-chan-title dis-module-title">{$chan.name}</div>
        {*<div class="dis-chan-title dis-module-title">
            <a href="chan?id={$chan.ID}">{$chan.name}</a>
        </div>*}
        <div class="dis-chan-param">
            <span class="dis-notice-count dis-corner-all">{$chan.info_num}</span>条新海报
        </div>
    </div>
</div>
