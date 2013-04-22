{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

    <div class="pm-page-left pm-page-top pm-inline-block">
            <div class="pm-module-title">写新私信</div>
            <div class="pm-message-ctrl pm-message-edit">
                <div class="pm-message-reciever pm-border"><input type="text" name="reciever" id="reciever" value="用户名"></input></div>
                <div class="pm-message-content"><textarea>私信内容</textarea></div>
                <div class="pm-ctrl"><a id="send" class="pm-light-button pm-send-message">发私信</a></div>
            </div>
            <div class="pm-content-border"></div>
            
            <div class="pm-module-title">私信列表</div>
            <div class="pm-message-list pm-message-user-list" type="message-user-list">
            {section name=i loop=$user_list}
                <div class="pm-border-line"></div>
                {include file="user/pmail.message.user.tpl" message=$user_list[i]}
            {sectionelse}
                <div class="pm-message-empty pm-empty-content">
                    你的私信箱是空的。
                </div>
            {/section}
            </div>
    </div><div class="pm-page-right pm-inline-block">
            {include file="comm/pmail.right.navi.tpl" view="message"}
            <div class="pm-content-border"></div>
            {include file="comm/pmail.idea.tpl"}
    </div>

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

{include file="comm/pmail.navi.tpl"}
<link type="text/css" title="style" href="css/pmail.page.css" rel="stylesheet"/>
<link type="text/css" title="style" href="css/pmail.user.css" rel="stylesheet"/>
<script type="text/JavaScript" src="js.min/core/pmail.date.min.js"></script>
<script type="text/JavaScript" src="js.min/user/pmail.message.min.js"></script>

</body>
</html>