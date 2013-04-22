
        <table class="pm-layout-table">
            {*<tr>
                <td class="name"><label for="realname">真实姓名：</label></td>
                <td><input type="text" name="realname" id="realname" value="{$user.realname}"></input></td>
            </tr>*}
            <tr>
                <td class="name"><label for="gender">性别：</label></td>
                <td><div class="pm-user-gender" gender="{$user.gender}">
                    <input type="radio" name="gender" id="gender_male" value="male"></input><label for="gender_male">男士</label>
                    <input type="radio" name="gender" id="gender_female" value="female"></input><label for="gender_female">女士</label>
                    <input type="radio" name="gender" id="gender_none" value="none"></input><label for="gender_none">不想说</label>
                </div></td>
            </tr>
            <tr>
                <td class="name"><label for="live_city">常驻城市：</label></td>
                <td><input type="text" name="live_city" id="live_city" value="{$user.live_city}"></input></td>
            </tr>
            <tr>
                <td class="name"><label for="contact">联系方式：</label></td>
                <td><div class="pm-border"><textarea class="pm-no-border" name="contact" id="contact">{$user.contact}</textarea></div></td>
            </tr>
        </table>
