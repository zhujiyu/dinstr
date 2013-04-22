<div class="pm-user-name"><a href="user?id={$user.ID}">{$user.username}</a></div>
{if $user.live_city}<span class="city"><span class="pm-inline-block pm-veri-data"><span class="pm-veri pm-icon ui-icon-person"></span></span>{$user.live_city}</span>&nbsp;{/if}
<span class="pm-user-param">
    订阅 <a href="user?id={$user.ID}&subscribe">{$user.param.subscribe_num}</a>&nbsp; 
    粉丝 <a href="user?id={$user.ID}&fans">{$user.param.fans_num}</a>&nbsp; 
    邮件 <a href="user?id={$user.ID}&mail">{$user.param.mail_num}</a>
</span>
<div class="pm-user-intro">{$user.self_intro|escape}</div>
<div class="pm-mail">
    <a href="mail?id={$user.mail.ID}">{$user.mail.content|escape:"quotes"|truncate_utf:60:"..."}</a>
</div>
