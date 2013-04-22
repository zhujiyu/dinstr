
            <div class="pm-module-title">{$channel.name}的标签</div>
            <div class="pm-tags-list">
            {section name=ti loop=$channel.tags}
                <a class="pm-tag" href="collect?item=channel&tag={$channel.tags[ti].ID}">{$channel.tags[ti].tag}</a>
            {sectionelse}
                <div class="pm-content">没有标签</div>
            {/section}
            </div>

            <div class="pm-content-border"></div>
            <div class="pm-module-title">邀请好友加入{$channel.name}</div>
            <div class="pm-content pm-channel-ctrl" id="{$channel.ID}">
                <span class="pm-inline-block pm-text"><span class="pm-icon ui-icon-person"></span></span>
                <a class="pm-invite-web">站内邀请</a>或者<a class="pm-invite-email">邮件邀请</a>
            </div>

            <div class="pm-content-border"></div>
            <div class="pm-module-title">{$channel.name} 的相关频道</div>
            {section name = i loop=$channels}
                {include file="chan/pmail.channel.simple.tpl" channel=$channels[i]}
            {/section}
