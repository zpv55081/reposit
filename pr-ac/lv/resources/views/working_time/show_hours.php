<style>
    .tr-super {
        position: -webkit-sticky;
        position: sticky;
        top: 30px;
        z-index: 2;
        padding: 5px;
    }
    a.text:active, 
    a.text:hover, 
    a.text {
    text-decoration: none;
    color: black;
    }
</style>

<center>
    <h3>РАБОЧИЕ ЧАСЫ</h3>
    <style type="text/css">
        #individual {
            display: none;
        }
    </style>
    <script type="text/javascript">
        function Go(Obj) {
            var i, od, o = document.getElementById(Obj.value)
            for (i = 0; i < Obj.options.length; i++) {
                od = document.getElementById(Obj.options[i].value)
                od.style.display = (od == o) ? 'inline' : 'none'
            }
        }
    </script>
    <center>
        <form action="show" method="get" name="city_date_filters">
            <table border="0" id="city_date_filters">
                <tr>
                    <td>
                        Расписание:
                    </td>
                    <td>
                        <select size="1" name="schedule_type" onchange='Go(this)'>
                            <option value="--">--</option>
                            <option value="simple">Простое</option>
                            <option value="individual">Индивидуальное</option>
                        </select>
                        <input type="hidden" id="--" />
                        <input type="hidden" id="simple" />
                        <select size="1" name="city" id="individual">
                            <?php foreach ($groups as $group) {
                                echo '<option value="' . $group->group_id . ':' . $group->group_name . '">
                        ' . $group->group_name . '
                        </option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Диапазон дат:</td>
                    <td>
                        с <input type="date" name="date_start" min="2010-01-01" max="2050-01-01">
                        по <input type="date" name="date_finish" min="2010-01-01" max="2050-01-01">
                    </td>
                </tr>
            </table>
            <input type="submit" class="button-grey" value="Отобразить">
        </form>
    </center>

    <h3><?php
        if ($city_name === '') {
            echo 'Простое расписание (для большинства населенных пунктов)';
        } else {
            echo 'Индивидуальное расписание: ' . $city_name;
        }
        ?>
    </h3>
    <table border="0" id="schedule">
        <tr class="tr-super">
            <td style="font-weight:bold;background:#AAAAAA;text-align:center;" title="Нажмите для прокрутки к сегодняшней дате">
            <a href="#current_date" class="text">ДАТА</a></td>
            <td style="font-weight:bold;background:#AAAAAA;text-align:center;" title="день недели">д.н.</td>
            <?php
            for ($i=0; $i<=23; $i++) {
                echo '<td style="font-weight:bold;background:#AAAAAA;" 
                title="с '.sprintf("%02d", $i).'ч.00мин до '.sprintf("%02d", $i+1).'ч.00мин">
                '.sprintf("%02d", $i).'..'.sprintf("%02d", $i+1).'</td>';
            }
            ?>
        </tr>
        <?php
        $week_days_abbreviated = array(1 => "пн", "вт", "ср", "чт", "пт", "сб", "вс");
        $row_time_html = '';
        foreach ($w_t as $working_time) {
            // если день недели суббота или воскресенье, выделяем шрифт ячейки красным
            if (date("N", strtotime($working_time->date)) > 5) {
                $td_text_color_by_week_day = '<td style="color:red;text-align:center;">';
            } else {
                $td_text_color_by_week_day = '<td style="text-align:center;">';
            }
            $row_time_html .= '
            <tr>
            ' . $td_text_color_by_week_day . date("d.m.Y", strtotime($working_time->date)) . '</td>
            ' . $td_text_color_by_week_day . '
            (' . $week_days_abbreviated[date("N", strtotime($working_time->date))] . ')';
            if ($working_time->date == date('Y-m-d', strtotime('-3 days'))) {
                $row_time_html .= '<a name="current_date"></a>';
            }
            $row_time_html .= '</td>';

            for ($h = 0; $h < 24; $h++) {
                if ($working_time->hours === ''){
                    $row_time_html .= '<td style="font-weight:bold;background:#FFFFCC;"title="нерабочий час"></td>';
                } elseif (in_array($h, (explode(';', $working_time->hours)))) {
                    $row_time_html .= '<td style="font-weight:bold;background:rgb(60,118,178);" title="РАБОЧИЙ ЧАС"></td>';
                } else {
                    $row_time_html .= '<td style="font-weight:bold;background:#FFFFCC;"title="нерабочий час"></td>';
                }
            }
            $row_time_html .= '</tr>';
        }
        echo $row_time_html;
        ?>
    </table>
    <?php echo my_vidtab(0, 'schedule');
    require_once 'lv/to_b/adapt_down.php';
    ?>          
