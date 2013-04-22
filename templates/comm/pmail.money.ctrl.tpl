<div class="pm-page-ctrl">
    <div class="pm-imoney-ctrl">
        <a class="pm-light-button pm-get-money">领取金币</a>
        <div class="">每天可以领取100个金币</div>
    </div>
    <div class="pm-content-border"></div>
        <table class="pm-imoney-list pm-layout-table">
            {section name=mli loop=$money_list}
            <tr>
                <td class="pm-imoney-item">{$money_list[mli].log_time|date_ago:"m-d H:i"}</td>
                {*<td>领取</td>*}
                <td>{$money_list[mli].imoney}</td>
            <tr>
            {/section}
        </table>
    <div class="pm-content-border"></div>
</div>
