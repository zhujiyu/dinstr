{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

    <div class="pm-page-left pm-inline-block">
        <div class="pm-page-navi ui-corner-top" view="{$view}">
            <a class="info item default">基本资料</a>
            <a class="pword item">修改密码</a>
            <a class="extend item">详细信息</a>
            <a class="priv item">隐私设置</a>
            <a class="deny item">黑名单</a>
        </div>

    <div class="pm-user-edit pm-object-edit" id="{$user.ID}">
        <div class="pm-edit-group pm-edit-info">
            {include file="user/pmail.edit.info.tpl"}
        </div>
        <div class="pm-edit-group pm-edit-extend">
            {include file="user/pmail.edit.extend.tpl"}
        </div>

    <table class="pm-edit-group pm-edit-pword pm-layout-table">
        <tr>
            <td class="name"><label for="oldpword">当前密码：</label></td>
            <td><input type="password" name="oldpword" id="oldpword"></input></td>
        </tr>
        <tr class="pm-veri-data pword">
            <td class="name"><label for="pword">新密码：</label></td>
            <td><input type="password" name="pword" id="pword"></input></td>
            <td class="desc-td"><span class="pm-veri"><span class="pm-icon ui-icon-check"></span></span><label class="pm-desc"></label></td>
        </tr>
        <tr class="pm-veri-data pword2">
            <td class="name"><label for="pword2">确认密码：</label></td>
            <td><input type="password" name="pword2" id="pword2"></input></td>
            <td class="desc-td"><span class="pm-veri"><span class="pm-icon ui-icon-check"></span></span><label class="pm-desc"></label></td>
        </tr>
    </table>
{*
    <div class="pm-edit-group pm-edit-tags">
        <div class="pm-edit-ctrl">
            <span class="pm-red-star pm-red pm-inline-block"></span>
            <label for="tags">标签：</label>
            <input type="text" name="tags" id="tags"></input>
            <a class="pm-tag-add pm-light-button">添加</a>
        </div>
        <div>{$user.username} 已经添加的标签：</div>
        <div class="pm-tag-list">
            {section name=ti loop=$user.tags}
                <span class="pm-tag" id="{$user.tags[ti].ID}">{$user.tags[ti].tag}</span>
            {/section}
        </div>
        <div class="desc">输入标签后点击添加，标签和昵称是唯一会被搜索到的关键词！</div>
    </div>
*}
    <div class="pm-edit-group pm-edit-priv">
        <div class="pm-module-title">隐私设置</div>
        <div class="pm-edit-item pm-edit-msg" style="width:300px;">
            <label for="msg_setting">私信设置：</label>
            <div class="pm-msg-setting pm-edit-option" set="{$user.msg_setting}">
                <input type="radio" name="msg_setting" id="msg_none" value="none"></input><label for="msg_none">不接受</label><br>
                <input type="radio" name="msg_setting" id="msg_follow" value="follow"></input><label for="msg_follow">仅我关注的人</label><br>
                <input type="radio" name="msg_setting" id="msg_officer" value="officer"></input><label for="atme_officer">仅我关注的人和同频道嘉宾</label><br>
                <input type="radio" name="msg_setting" id="msg_all" value="all"></input><label for="msg_all">所有人</label>
            </div>
        </div>
        <div class="pm-edit-item pm-edit-atme">
            <label for="atme_setting">@我设置：</label>
            <div class="pm-atme-setting pm-edit-option" set="{$user.atme_setting}">
                <input type="radio" name="atme_setting" id="atme_none" value="none"></input><label for="atme_none">不接受</label><br>
                <input type="radio" name="atme_setting" id="atme_follow" value="follow"></input><label for="atme_follow">仅我关注的人</label><br>
                <input type="radio" name="atme_setting" id="atme_officer" value="officer"></input><label for="atme_officer">仅我关注的人和同频道嘉宾</label><br>
                <input type="radio" name="atme_setting" id="atme_all" value="all"></input><label for="atme_all">所有人</label>
            </div>
        </div>
    </div>

    <div class="pm-edit-group pm-edit-deny">
        <div class="desc">被你加入黑名单的用户，将不能关注你、给你发私信、回复你的评论、评论你的话题、邀请你加入频道参与话题。但他们仍然可以看到你的公开信息，以及你在任何频道的公开信息。 </div>
        <div class="pm-user-list">
        {section name=ui loop=$deny_users}
            <div class="pm-border-line"></div>
            <div class="pm-user">
                <div class="pm-user-avatar pm-inline-block">
                    {include file="user/pmail.user.avatar.tpl" user=$deny_users[ui] type="pm-avatar-middle"}
                </div>
                <div class="pm-user-info pm-inline-block">
                    {include file="user/pmail.user.info.tpl" user=$deny_users[ui]}{* $deny_users[ui].username}{$deny_users[ui].self_intro *}
                </div>
                <div class="pm-user-ctrl pm-ctrl pm-inline-block">
                    <a class="pm-gray-button" id="cancel">取消黑名单</a>
                </div>
            </div>
        {/section}
        </div>
    </div>

        <div class="pm-ctrl"><a class="pm-light-button save">保存</a></div>
    </div>

    </div><div class="pm-page-right pm-inline-block">
        {*<div class="pm-content-navi ui-corner-top" view="{$view}">
            <a class="info item default">基本资料</a>
            <a class="pword item">修改密码</a>
            <a class="extend item">详细信息</a>
            <a class="tags item">个人标签</a>
            <a class="priv item">隐私设置</a>
            <a class="deny item">黑名单</a>
        </div>*}
    </div>

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

{include file="comm/pmail.navi.tpl"}
<link type="text/css" title="style" href="css/pmail.page.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.user.css" rel="stylesheet"/>
<script type="text/JavaScript" src="js.min/core/pmail.photo.min.js"></script>
<script type="text/JavaScript" src="js.min/user/pmail.user.veri.min.js"></script>
<script type="text/JavaScript" src="js.min/user/pmail.user.setting.min.js"></script>

</body>
</html>