{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

    {if $view == 'apply'}
        <div class="pm-apply pm-plugin">
            <div class="pm-success-prompt">
                <h3>申请已成功发送！</h3>
                <p>请耐心等待，我们会尽快和你联系！</p>
            </div>
            {if $apply_succeed}
            <div class="pm-success-prompt">
                <h3>申请已成功发送！</h3>
                <p>请耐心等待，我们会尽快和你联系！</p>
            </div>
            {/if}
            <div class="pm-module-title pm-plugin-title">申请注册码</div>
            <form class="pm-plugin-content pm-inline-block" action="" method="post">
                <input type="hidden" name="p" value="fb"/>
                <input type="hidden" name="view" value="apply"/>
                <div class="pm-plugin-email pm-border"><input type="text" class="pm-no-border"
                    name="email" id="email" value="邮箱地址"></input></div>
                <div class="pm-plugin-intro pm-border"><textarea name="intro" id="intro"
                    class="pm-no-border">介绍下自己职业/身份/背景，有助于我们为你选择合适的频道加入。</textarea></div>
                <div class="pm-plugin-ctrl pm-ctrl">
                    <input type="submit"></input><a class="pm-light-button" id="ok">确定</a></div>
            </form>
            <div class="pm-plugin-desc pm-inline-block">
                <div class="pm-module-title">你也可以通过以下方式申请注册码</div>
                <div class="pm-content">1. 关注微博，并私信我们</div>
                <div class="pm-content">2. 直接邮件联系我们</div>
            </div>
        </div>
    {elseif $view == 'feedback'}
        <div class="pm-feedback pm-plugin">
            <a href="home"><strong>返回首页</strong></a>
            {if $feedback_succeed}
            <div class="pm-success-prompt">
                <h3>建议保存成功！</h3>
                <p>{$app.name}感谢您的支持和您提供的宝贵建议！</p>
            </div>
            {/if}
            
            <div class="pm-module-title pm-plugin-title">给{$app.name}提建议或意见</div>
            <form class="pm-plugin-content pm-inline-block" action="" method="post">
                <input type="hidden" name="p" value="fb"/>
                <input type="hidden" name="view" value="feedback"/>
                <div class="pm-plugin-intro pm-border"><textarea name="intro" id="intro"
                    class="pm-no-border"></textarea></div>
                <div class="pm-plugin-ctrl pm-ctrl">
                    <input type="submit"></input><a class="pm-light-button" id="ok">确定</a></div>
            </form>
            <div class="pm-plugin-desc pm-inline-block">
                <div class="pm-module-title">你也可以通过以下方式提建议和意见</div>
                <ul><li>1. 关注微博，并私信我们</li>
                <li>2. 直接邮件联系我们</li></ul>
            </div>
        </div>
    {elseif $view == 'list'}
        <div class="pm-plugin">
            <div class="pm-page-navi" view={$item}>
                <a class="item default feedback" href="using?p=fb&view=list&item=feedback">意见反馈</a>
                <a class="item apply" href="using?p=fb&view=list&item=apply">意见反馈</a>
            </div>
            <table class="pm-layout-table">
            {section name=i loop=$datas}
                <tr id="{$datas[i].ID}"><td>{if $datas[i].user}<a href="user?id={$datas[i].user_id}" target="_blank">{$datas[i].user.username}</a>{else}{$datas[i].user_id}{/if}</td>
                <td>{$datas[i].content}</td><td>{$datas[i].status}</td></tr>
            {/section}
            </table>
        </div>
    {/if}

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div><!-- end of page --> </div> <!-- end of wrap -->

{if $user}
    {include file="comm/pmail.navi.tpl"}
{else}
    {include file="comm/pmail.guest.top.tpl"}
{/if}
<link type="text/css" title="style" href="css/pmail.plugin.css" rel="stylesheet"/>

</body>
</html>