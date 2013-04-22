<div class="so-news-good so-news-good-small ui-corner-all" goodid="{$good.ID}" source="{$good.source}"
    numiid="{$good.num_iid}" cusurl="0" user="{$user.id}">
    <div class="so-good-photo so-inline-block so-load-on-display-pic" imgsrc="{$good.pic_url}">
        <img style="display: none;"/>
    </div>
    <div class="so-good-info so-inline-block">
        <div class="so-good-info-title">
            <a href="{$good.click_url}" target="_blank">{$good.title|strip}</a>
        </div>
        <div class="so-good-info-main">价格：
            <span class="price">
                {if $good.price}{$good.price}{elseif $good.price_url}<img src="{$good.price_url}"/>{/if}
            </span>
            <span class="source">来自：
                {if $good.source == "taobao"}
                <a href="http://www.taobao.com" target="_blank">淘宝网</a>
                {elseif  $good.source == "tmall"}
                <a href="http://www.tmall.com" target="_blank">淘宝商城</a>
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
            </span>
        </div>
        <div class="so-good-info-extend">
            {if $good.shop}<span class="shop">店铺：
                <a href="{$good.shop_click_url}" target="_blank">{$good.shop}</a>
            </span>{/if}
            {if $good.item_location}<span class="location">地址：
                <span>{$good.item_location}</span>
            </span>{/if}
        </div>
        <div class="so-good-ctrl so-inline-block">
            <a class="so-good-buy  ui-corner-all">购买</a>
            <a class="so-good-keep ui-corner-all">收藏</a>
            <a class="so-good-keep ui-corner-all">详情统计</a>
        </div>
        <div class="so-good-desc">{$desc}（本商品由<a href="user?ID="{$good.user_id}">@{$good.user.username}</a>发布）</div>
    </div>
</div>
