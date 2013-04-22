<div class="pm-mail-top">
    <a href="user?id={$user.ID}">{$user.username}{if $user.sign} - {$user.sign}{/if}</a>
    {if $channel}
    <span class="pm-mail-release pm-channel-link" id="{$channel.ID}" style="float: right;"><a href="chan?id={$channel.ID}">
        <span class="pm-inline-block pm-icon-wrap"><span class="pm-icon ui-icon-signal-diag"></span></span>
        {$channel.name}
        <div class="pm-avatar-img pm-load-display pm-avatar-small pm-inline-block" imgsrc="{$channel.logo.small}" title="{$channel.name}"></div>
    </a></span>
    {/if}
</div>