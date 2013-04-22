{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

    <div class="pm-page-left pm-inline-block">

<form class="pm-channel-create pm-object-edit" action="" method="get">
    {*<div class="pm-module-title pm-edit-title">开通新频道</div>*}
    <input type="hidden" name="p" value="create"/>
    <input type="hidden" name="type" value="social"/>
    <div class="pm-edit-item pm-veri-data" id="channel-name">
        <span class="pm-red-star pm-red pm-inline-block"></span>
        <label for="name">名称：</label>
        <input type="text" name="name" id="name" value=""></input>
        <span class="pm-veri pm-inline-block"><span class="pm-icon ui-icon-check"></span></span>
        <label id="err" class="pm-desc">该频道已经存在！</label>
        <div class="desc">频道名要简介清楚地表达出频道的主旨！</div>
    </div>
    <div class="pm-content-border"></div>
    <div class="pm-edit-item pm-edit-logo">
        <div class="pm-module-title">上传频道LOGO</div>
        <input type="hidden" name="logo" id="logo"/>
        <div class="pm-logo-list pm-inline-block">
            <div class="pm-avatar-img pm-avatar-big pm-tile-img"><img src="css/logo/chanbgb.png"/></div>
        </div>
        <div class="pm-upload-ctrl pm-inline-block">
            <input type="file" id="pm-upload-file" name="pm-upload-file"></input>
            <a class="pm-light-button pm-upload-file-button">上传</a>&nbsp;
            <img class="uploading" src="css/images/loading.gif"/>
        </div>
    </div>
    <div class="pm-content-border"></div>
    <div class="pm-edit-item pm-edit-type">
        <span class="pm-red-star pm-red pm-inline-block"></span>
        <label for="name">频道类型：</label>
        <ul class="pm-content">
            <li><input type="radio" name="type" id="social" value="social"></input><label for="social">社交类——兴趣爱好，同行同事，同学校友，老乡同城</label></li>
            <li><input type="radio" name="type" id="business" value="business"></input><label for="business">商务类——网络商城，品牌厂家，生活服务，本地消费</label></li>
            <li><input type="radio" name="type" id="info" value="info"></input><label for="info">资讯类——商务资讯，招聘求职，书评影讯，婚恋交友</label></li>
            <li><input type="radio" name="type" id="news" value="news"></input><label for="news">新闻类——社会新闻，流行时尚，科技热点</label></li>
        </ul>
    </div>
    <div class="pm-edit-item">
        <span class="pm-red-star pm-red pm-inline-block"></span>
        <label for="tags">标签：</label>
        <div class="pm-border"><textarea name="tags" id="tags" class="pm-no-border"></textarea></div>
        <div class="desc">以空格或逗号隔开多个标签，除频道名称外，标签是唯一会被搜索到的关键词，也是分类的依据！</div>
    </div>
    <div class="pm-edit-item">
        <div class="pm-module-title">隐私设置</div>
        <input type="checkbox" name="member_opened" id="member_opened" value="1" checked/>
        <label for="member_opened">公开频道会员（嘉宾）信息</label>
        <div class="desc">选中此项，频道会员（嘉宾）信息任何人都可以查看，否则仅本频道会员可以查看。</div>
    </div>
    <div class="pm-content-border"></div>
    <div class="pm-edit-item pm-edit-desc">
        <div class="pm-module-title">频道介绍</div>
        <div class="pm-border"><textarea class="pm-no-border" name="desc" id="desc"></textarea></div>
    </div>
    <div class="pm-ctrl">
        <input type="submit" value="保存"/><a class="pm-light-button pm-save" id="save">保存</a>
    </div>
</form>

    </div><div class="pm-page-right pm-inline-block">
        <div class="pm-module-title">关于创建频道</div>
        <div class="pm-desc">
            <div class="pm-desc-item"><a href="help?">为什么是私有频道？</a></div>
            <div class="pm-desc-item"><a href="help?">什么是公共频道？</a></div>
        </div>
    </div>

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

{include file="comm/pmail.navi.tpl"}
<link type="text/css" title="style" href="css/pmail.page.css" rel="stylesheet"/>
<script type="text/JavaScript" src="js.min/jquery/jquery.ui.resizable.min.js"></script>
<script type="text/JavaScript" src="js.min/core/pmail.photo.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.chan.edit.min.js"></script>

</body>
</html>