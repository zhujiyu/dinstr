<form class="dis-search-form dis-border dis-corner-all" method="get" action="search">
    <input type="text" name="keyword" id="keyword" value="搜栏目" class="dis-no-border">
    </input><input type="submit" id="search" value=""></input>
</form>
<div class="dis-chan-navi">
{section name=cli loop=$chan_list}
    <div class="dis-chan-item dis-box-shadow ui-corner-all" id="{$chan_list[cli].ID}" title="{$chan_list[cli].name}">
        <div class="dis-chan-logo dis-inline-block">
            <div class="dis-avatar-middle dis-tile-img dis-load-display" imgsrc="{$chan_list[cli].logo.small}"></div>
        </div><div class="dis-chan-param dis-inline-block">
            <div class="dis-chan-title dis-module-title">{$chan_list[cli].name}</div>
            <span class="dis-notice-count dis-corner-all">{$chan_list[cli].info_num}</span>条新海报
        </div>
    </div>
{/section}
</div>
    {*include file="objs/navi.chan.obj.tpl" chan=$chan_list[cli]*}
    {*if !$smarty.section.cli.last}<div class="dis-border-line"></div>{/if*}
