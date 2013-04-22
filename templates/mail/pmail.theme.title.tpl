<div class="pm-theme pm-theme-title" id={$theme.ID}>
    <a class="title" href="mail?id={$theme.mail_id}">
        {if $flow && $flow.weight}{$flow.weight}&nbsp;@&nbsp;{/if}{$theme.content|escape:3}
    </a>
    <span class="pm-theme-ctrl">
        {if $theme.status.interest}<a class="pm-interest-cancel">取消关注</a>{else}<a class="pm-interest">关注</a>{/if}&nbsp;|&nbsp;
        {if $theme.status.approved}<a class="pm-approve-cancel">取消参与</a>{else}<a class="pm-approve">参与</a>{/if}
    </span>
</div>
