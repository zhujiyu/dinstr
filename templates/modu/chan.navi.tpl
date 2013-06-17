<div class="dis-navi-logo">
    <a href="index">{include file="comm/logo.comm.tpl"}</a>
</div>
<div class="dis-content-border"></div>
<div class="dis-chan-navi">
{section name=cli loop=$chan_list}
    <div class="dis-chan-item ui-corner-all" id="{$chan_list[cli].ID}" title="{$chan_list[cli].name}">
        <div class="dis-chan-logo dis-inline-block">
            <div class="dis-avatar-middle dis-load-display" imgsrc="{$chan_list[cli].logo.small}"></div>
        </div><div class="dis-chan-param dis-inline-block">
            <div class="dis-chan-title dis-module-title">{$chan_list[cli].name}</div>
            <span class="dis-notice-count dis-corner-all"><param>{$chan_list[cli].info_num}</param>条新海报</span>
        </div>
    </div>
{/section}
</div>
<div class="dis-content-border"></div>
<form class="dis-search-form dis-border" method="get" action="search">
    <input type="text" name="keyword" id="keyword" value="搜栏目" class="dis-no-border">
    </input><input type="submit" id="search" value=""></input>
</form>
{*
<div class="dis-chan-navi"></div>
*}
    {*include file="objs/navi.chan.obj.tpl" chan=$chan_list[cli]*}
    {*if !$smarty.section.cli.last}<div class="dis-border-line"></div>{/if*}
