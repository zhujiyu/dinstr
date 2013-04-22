<div class="pm-veri-data pm-edit-item pm-edit-email">
    <div class="name pm-inline-block"><span class="pm-red-star pm-red"></span><label for="email">邮箱：</label></div>
    <div class="ctrl pm-inline-block"><input type="text" name="email" id="email" readonly value="{$user.email}" class="pm-no-border"></input></div>
</div>
<div class="pm-veri-data pm-edit-item pm-edit-name">
    <div class="name pm-inline-block"><span class="pm-red pm-red-star"></span><label for="uname">用户名：</label></div>
    <div class="ctrl pm-inline-block">
        <input type="text" name="uname" id="uname" value="{$user.username}"></input>
        <label class="pm-desc" id="err">该用户名已经存在！</label>
        <span class="pm-veri pm-inline-block"><span class="pm-icon ui-icon-check"></span></span>
    </div>
</div>
<div class="pm-edit-item pm-edit-sign">
    <div class="name pm-inline-block">
        <label for="sign">简短签名：</label>
    </div><div class="ctrl pm-inline-block pm-border">
        <input class="pm-no-border" name="sign" id="sign" value="{$user.sign}"></input>
    </div>
</div>
<div class="pm-content-border"></div>

<div class="pm-edit-item pm-edit-logo">
    <div class="name pm-inline-block">头像：</div>
    <div class="ctrl pm-inline-block">
        <div class="pm-inline-block">
            <div class="pm-avatar-img pm-avatar-big pm-tile-img pm-load-display"
                    imgsrc="{$user.avatar.big}"><img /></div>
        </div>
        <div class="pm-inline-block pm-edit-ctrl pm-upload-photo-ctrl">
            <input type="file" id="pm-upload-file" name="pm-upload-file"></input>
            <a class="pm-light-button pm-upload-file-button">上传</a>
            <img class="uploading" src="css/images/loading.gif"/>
        </div>
    </div>
</div>
<div class="pm-content-border"></div>

<div class="pm-edit-item pm-edit-desc">
    <div class="name pm-inline-block">
        <label for="desc">个人简介：</label>
    </div><div class="pm-border pm-inline-block">
        <textarea class="pm-no-border" name="desc" id="desc">{$user.self_intro}</textarea>
    </div>
</div>
<div class="pm-content-border"></div>

{*
        <table class="pm-layout-table">
            <tr class="pm-veri-data pm-edit-email">
                <td class="name"><span class="pm-red-star pm-red"></span><label for="email">邮箱：</label></td>
                <td class="ctrl"><input type="text" name="email" id="email" readonly value="{$user.email}" class="pm-no-border"></input></td>
            </tr>
            <tr class="pm-veri-data pm-edit-name">
                <td class="name"><span class="pm-red pm-red-star"></span><label for="name">昵称：</label></td>
                <td class="ctrl"><input type="text" name="uname" id="uname" value="{$user.username}"></input>
                    <label class="pm-desc" id="err">该昵称已经存在！</label>
                    <span class="pm-veri pm-inline-block"><span class="pm-icon ui-icon-check"></span></span></td>
            </tr>
            <tr class="pm-edit-item pm-edit-logo">
                <td class="name">头像：</td><td>
                <div class="pm-inline-block"><div class="pm-avatar-img pm-avatar-big pm-tile-img pm-load-display"
                    imgsrc="{$user.avatar.big}"><img /></div></div>
                <div class="pm-inline-block pm-edit-ctrl pm-upload-photo-ctrl">
                    <input type="file" id="pm-upload-file" name="pm-upload-file"></input>
                    <a class="pm-light-button pm-upload-file-button">上传</a>
                    <img class="uploading" src="css/images/loading.gif"/></div></td>
                <td></td>
            </tr>
            <tr class="pm-edit-item pm-edit-sign">
                <td class="name"><label for="sign">简短签名：</label></td>
                <td><div class="pm-border"><textarea class="pm-no-border" name="sign" id="sign">{$user.sign}</textarea></div></td>
            </tr>
            <tr class="pm-edit-item pm-edit-desc">
                <td class="name"><label for="desc">个人简介：</label></td>
                <td><div class="pm-border"><textarea class="pm-no-border" name="desc" id="desc">{$user.self_intro}</textarea></div></td>
            </tr>
        </table>
*}