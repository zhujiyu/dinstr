<div class="pm-good pm-good-small pm-inline-block" goodid="{$good.ID}" source="{$good.source}"
    numiid="{$good.num_iid}" cusurl="0" user="{$user.id}" title="{$good.title}">
    <div class="pm-good-photo pm-load-display" imgsrc="{$good.pic_url}">
    </div><div class="pm-good-info">
        <div class="pm-good-title">
            <a href="{$good.click_url}" target="_blank">{$good.title|strip}</a>
        </div>
        <div class="pm-good-base">
            <div class="pm-good-price">
                {if $good.price}￥<span>{$good.price}</span>元{elseif $good.price_url}<img src="{$good.price_url}"/>{/if}
            </div>
            <div class="pm-good-source">
                {if $good.shop}
                    <a href="{$good.shop_url}" target="_blank">{$good.shop}</a><span style="display:inline-block">&nbsp;
                {/if}
                {if $good.source == "taobao"}
                    <a href="http://www.taobao.com" target="_blank">淘宝网</a>
                {elseif  $good.source == "tmall"}
                    <a href="http://www.tmall.com" target="_blank">天猫商城</a>
                {elseif  $good.source == "jingdong"}
                    <a href="http://www.360buy.com" target="_blank">京东商城</a>
                {elseif  $good.source == "m18"}
                    <a href="http://www.m18.com" target="_blank">麦考林</a>
                {elseif  $good.source == "dangdang"}
                    <a href="http://www.dangdang.com" target="_blank">当当</a>
                {elseif  $good.source == "amazon"}
                    <a href="http://www.amazon.cn" target="_blank">卓越亚马逊</a>
                {elseif  $good.source == "vancl"}
                    <a href="http://www.vancl.com" target="_blank">凡客诚品</a>
                {elseif  $good.source == "yihaodian"}
                    <a href="http://www.yihaodian.com" target="_blank">1号店</a>
                {elseif  $good.source == "moonbasa"}
                    <a href="http://www.moonbasa.com" target="_blank">梦芭莎</a>
                {elseif  $good.source == "masamaso"}
                    <a href="http://www.masamaso.com" target="_blank">玛萨玛索</a>
                {else}
                    <a href="" target="_blank"></a>
                {/if}
                {if $good.shop}</span>{/if}
            </div>
        </div>
        <div class="pm-good-ctrl">
            <a class="pm-light-button pm-good-buy" href="{$good.click_url}" target="_blank">购买</a>
            <a class="pm-gray-button pm-good-keep">收藏</a>
        </div>
        <div class="pm-good-info-extend">
            {if $good.item_location}<div class="pm-good-location">
                地址：<span>{$good.item_location}</span>
            </div>{/if}
            <div class="pm-good-desc pm-content">{$desc}</div>
            <div class="pm-good-publisher">
                (商品由<a href="user?ID="{$good.user_id}">@{$good.user.username}</a>发布)
            </div>
        </div>
    </div>
    <div class="pm-good-cart pm-corner-all"><img ></div>
</div>