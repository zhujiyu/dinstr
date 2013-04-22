<div class="pm-channel-ctrl" id="{$channel.ID}">
    {if $status.role > 0}
        <span class="pm-gray-button"><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-volume-on"></span></span>&nbsp;已加入&nbsp;|&nbsp;<a class="pm-quit">退出</a></span>
    {elseif $status.role == 0}
        <a class="pm-apply-join pm-light-button"><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-volume-off"></span></span>&nbsp;申请加入</a>
        <span class="pm-gray-button"><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-pin-w"></span></span>&nbsp;已订阅&nbsp;|&nbsp;<a class="pm-subscribe-cancel">取消</a></span>
    {else}
        <a class="pm-apply-join pm-light-button"><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-volume-off"></span></span>&nbsp;申请加入</a>
        <a class="pm-subscribe pm-light-button"><span class="pm-icon-wrap pm-inline-block"><span class="pm-icon ui-icon-pin-s"></span></span>&nbsp;订阅频道</a>
    {/if}
</div>