{include file="comm/pmail.header.tpl"}
<body>

{*<div class="pm-navi-wrap"></div>
<div class="pm-navi">
    <table class="pm-navi-content pm-login-top pm-layout-table"><tr>
        <td style="width:200px;">{include file="comm/pmail.logo.tpl"}</td>
        <td style="" class="pm-login-simple">{include file="user/pmail.login.form.tpl"}</td>
        <td style=""><a class="pm-light-button">注册</a></td>
        <td><a class="pm-button-up" href="login?v=test"><div class="head"></div><div class="tear"></div></a></td>
    </tr></table>
</div>*}

<div class="pm-wrap">
    <div class="pm-page">
        <div class="pm-content-border"></div>
        <div class="pm-desc pm-pindao-desc">
                <!-- 简介 -->
            <div style="padding: 20px 0; font-size: 28px; font-weight: bold; text-align: center;">理解{$app.name}是什么？</div>
            <div class="pm-content">
                {$app.name}首先是互联网上各个实务网站的公共服务平台，具体说可以是各家购物网站的公共客服平台。
            </div>
            <div class="pm-content">
                在{$app.name}里，你可以找到自己所属的群体，成为该群体的一份子，在这里，任何用户都可以获得代表群体发言的机会，并得到最好的服务！
            </div>
            <div class="pm-content">
                {$app.name}不只是信息服务平台，它不是社交网络，也不同于搜索引擎，在这里，您将得到前所未有的重视和尊重，您将成为整个信息世界中心的。
            </div>
            <img style="max-width: 900px;" src="css/images/pindao1.png"/>

                <!-- 介绍频道 -->
            <div class="pm-desc-img pm-inline-block"><img src="css/images/pindao2.png"/></div>
            <div class="pm-desc-content pm-inline-block">
                <div class="pm-module-title">什么是频道、嘉宾、粉丝？</div>
                <div class="pm-content-border"></div>
                <div class="pm-content">
                    频道是为某种特定用户群提供信息服务的一种网络信息渠道，由主持人、嘉宾、粉丝三部分组成。
                </div>
                <div class="pm-content">
                    嘉宾是频道的主体和服务对象，是频道的上游用户，信息的发布者；主持人是频道的管理员和服务者；
                    粉丝是频道的下游用户，信息的接收者，有对频道的嘉宾群体感兴趣，希望接收其信息或者为其提供某种线上或线下服务的人组成。
                </div>
                <div class="pm-content">
                    频道的信息流是由嘉宾控制的，嘉宾可以往频道发布公开和私密信息，
                    公开信息会自动分发到其他频道嘉宾和粉丝的个人首页上，私密信息则只分发给其他嘉宾，只在频道内部交流。
                    因此，频道即可以是一个大众的信息服务工具，也可以成为一个同类用户的小众交流社区。
                </div>
            </div>
            <div class=""></div>

                <!-- 介绍话题 -->
            <div class="pm-desc-content pm-inline-block">
                <div class="pm-module-title">什么是话题，有哪些操作？</div>
                <div class="pm-content-border"></div>
                <div class="pm-content">
                    话题是频道内部信息的组织形式，它可以包含各种信息，也可以是某种活动。
                </div>
                <div class="pm-content">
                    话题有三者操作：关注、评论、赞同。
                    嘉宾和粉丝都可以关注一个感兴趣的话题，随时掌握该话题的新评论和赞同。
                    用户可以通过评论向该话题添加新信息。赞同是一种自定义的操作，其作用有各话题自行定义。
                </div>
                <div class="pm-content">
                    
                </div>
            </div>
            <div class="pm-desc-img pm-inline-block"><img src="css/images/pindao1.png"/></div>

        </div>
    </div>
    <div class="pm-content-border"></div>
    {include file="comm/pmail.footer.tpl"}
</div>

{include file="comm/pmail.guest.top.tpl"}

</body>
</html>
