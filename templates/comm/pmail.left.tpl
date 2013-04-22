
<ul class="pm-content-navi" view="{$view}">
    <li class="feed item"><a href="home?feed">最新邮件</a></li>
    <li class="important item"><a href="home?important">重要邮件</a></li>
    <li class="reply item"><a href="home?reply">回复我的</a></li>
    <div class="pm-content-border"></div>
    <li class="interest item"><a href="ls?interest">关注主题</a></li>
    <li class="approve item"><a href="ls?approve">参与主题</a></li>
    <li class="collect item"><a href="ls?collect">我的收藏</a></li>
    <div class="pm-content-border"></div>
    <li class="publish item"><a href="ls?publish">邮件({$user.param.mail_num})</a></li>
    {*<li class="message item"><a href="msg">私信({$user.param.msg_notice})</a></li>*}
    <li class="channel item"><a href="ls?channel">频道({$user.param.join_num}/{$user.param.subscribe_num})</a></li>
    <li class="friend item"><a href="ls?friend">好友({$user.param.follow_num}/{$user.param.fans_num})</a></li>
</ul>
