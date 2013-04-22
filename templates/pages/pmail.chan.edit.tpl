{include file="comm/pmail.header.tpl"}
<body>

<div class="pm-wrap"><div class="pm-page">
    {if $err}
        <div class="pm-err">{$err}</div>
        <div class="pm-content-border"></div>
    {/if}

    <div class="pm-page-left pm-inline-block">
    {*<div class="pm-module-title pm-edit-title">编辑频道：{$channel.name}</div>*}
        <h3>编辑频道：{$channel.name}</h3>
        <div class="pm-page-navi" view="{$view}">
            <a class="default setting item">基本资料</a>
            <a class="tags item">频道标签</a>
        </div>

<form class="pm-channel-edit pm-object-edit" id="{$channel.ID}">
    <input type="hidden" name="p" value="edit"/>

    <div class="pm-edit-group pm-edit-info">
        <div class="pm-edit-item pm-veri-data pm-edit-name">
            <span class="name pm-module-title pm-inline-block">
                <span class="pm-red-star pm-red pm-inline-block"></span>
                <label for="name">名称：</label>
            </span>
            <input type="text" name="name" id="name" value="{$channel.name}"></input>
            <label id="err" class="pm-desc">该频道已经存在！</label>
            <span class="pm-veri pm-inline-block"><span class="pm-icon ui-icon-check"></span></span>
            <div class="desc">频道名最好能简介清楚地表达出频道的主旨！</div>
        </div>
        <div class="pm-content-border"></div>

        <div class="pm-module-title">上传频道LOGO</div>
        <div class="pm-edit-item pm-edit-logo">
            <input type="hidden" name="logo" id="logo"/>
            <div class="pm-logo-list pm-inline-block">
                <div class="pm-avatar-img pm-avatar-big pm-tile-img pm-load-display"
                    imgsrc="{$channel.logo.big}"><img /></div>
            </div>
            <div class="pm-upload-photo-ctrl pm-edit-ctrl pm-inline-block">
                <input type="file" id="pm-upload-file" name="pm-upload-file"></input>
                <a class="pm-light-button pm-upload-file-button">上传</a>
                <img class="uploading" src="css/images/loading.gif"/>
            </div>
            <div class="desc">上传新的图片后，必须点“保存“，才能修改该频道的LOGO！</div>
        </div>
        <div class="pm-content-border"></div>
        <div class="pm-edit-item pm-edit-desc">
            <div class="pm-module-title">频道介绍</div>
            <div class="pm-border"><textarea class="pm-no-border" name="desc" id="desc">{$channel.description}</textarea></div>
        </div>
    </div>

    <div class="pm-edit-group pm-edit-item pm-edit-tags">
        <div class="pm-edit-ctrl">
            <span class="pm-red-star pm-red pm-inline-block"></span>
            <label for="tags">标签：</label>
            <input type="text" name="tags" id="tags"></input>
            <a class="pm-tag-add pm-light-button">添加</a>
        </div>
        <div>{$channel.name} 频道已经存在的标签：</div>
        <div class="pm-tag-list">
            {section name=ti loop=$channel.tags}
                <span class="pm-tag" id="{$channel.tags[ti].ID}">{$channel.tags[ti].tag}</span>
            {/section}
        </div>
        <div class="desc">输入标签后点击添加，标签和名称是唯一会被搜索到的关键词，也是分类的依据！</div>
    </div>

    <div class="pm-ctrl">
        <a class="pm-light-button pm-save" id="save">保存</a>
    </div>
</form>

    </div><div class="pm-page-right pm-inline-block ui-corner-all" id="addition">
    </div>

    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div> <!-- end of page --> </div> <!-- end of wrap -->

{include file="comm/pmail.navi.tpl"}
<link type="text/css" title="style" href="css/pmail.page.css" rel="stylesheet"/>
<script type="text/JavaScript" src="js.min/core/pmail.photo.min.js"></script>
<script type="text/JavaScript" src="js.min/chan/pmail.chan.edit.min.js"></script>

</body>
</html>