<style type="text/css">
    #individual {
        display: none;
    }
</style>
<link rel="stylesheet" type="text/css" href="../../css/jquery-ui.multidatespicker.css">
<script type="text/javascript">
    function Go(Obj) {
        var i, od, o = document.getElementById(Obj.value)
        for (i = 0; i < Obj.options.length; i++) {
            od = document.getElementById(Obj.options[i].value)
            od.style.display = (od == o) ? 'inline' : 'none'
        }
    }
</script>
<script type="text/javascript" src="../../config/plugins/jquery-ui.multidatespicker.js">
</script>
<script>
    $(document).ready(function() {

        let dates = [];

        let control_date = $('#control_date').val().split(';');


        for (var i = 0; i < control_date.length; i++) {
            control_date[i] = control_date[i];
        }
        console.debug(control_date);
        tigra_tables($('.tigra').attr('id'), '0', '0', '#BAFF95', '#DAFFC5', '#CCCCCC', '#FFCC80');

        if ($('#control_date').val() != '') {
            for (var i = 0; i < control_date.length; i++) {
                var date = control_date[i].split('-');
                dates[i] = new Date(date[0], date[1] - 1, date[2]);
            }
        } else {
            dates = null;
        }

        $('#dates').multiDatesPicker({
            addDates: dates,
            minDate: new Date(<?= date('Y', strtotime('-5 year')) ?>, 0, 1),
            maxDate: new Date(<?= date('Y', strtotime('+5 year')) ?>, 12, 0),
            onSelect: function(date) {
                $('#control_date').val();
                let picker_dates = $(this).multiDatesPicker('getDates');
                let picker_dates_string = '';
                for (let i = 0; i < picker_dates.length; i++) {
                    let splited = picker_dates[i].split('.');
                    if (splited[0] != NaN) {
                        picker_dates_string += splited[2] + '-' + splited[1] + '-' + splited[0] + ';';
                    }
                }
                $('#control_date').val(picker_dates_string);
            },
        });
    });
</script>
<center>
    <details>
        <summary>Шаблоны</summary>
        <form action="edit" method="get">
            <table>
                <tr>
                    <td>Массовый интервал дат:</td>
                    <td>
                        с <input type="date" name="date_start" min="<?= date('Y-m-d', strtotime('-5 years')) ?>" max="<?= date('Y-m-d', strtotime('+5 years')) ?>">
                        по <input type="date" name="date_finish" min="<?= date('Y-m-d', strtotime('-5 years')) ?>" max="<?= date('Y-m-d', strtotime('+5 years')) ?>">
                    </td>
                    <td><input name="default_dates_kind" type="radio" value="monfri" required>Пн-Пт</td>
                    <td style="color:red"> <input name="default_dates_kind" type="radio" value="satsun">Сб-Вс</td>
                    <td title="Устанавливая рабочие часы для Пн-Пт, не забудьте 
затем установить часы (в т.ч. нерабочие) для Сб-Вс">
                        <input type="submit" class="button-grey" name="set" value="Применить шаблон">
                    </td>
                </tr>
            </table>
        </form>
    </details>

    <h3>РЕДАКТОР РАБОЧИХ ЧАСОВ</h3>
    <form action="update" method="post">
        <input type="hidden" name="_method" value="get">
        <table border="0" id="city_date_filters">
            <tr>
                <td>
                    <b>Расписание:</b>
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
        </table>
        <br>
        <div style="display: inline-block;margin:auto;padding:5px;border: 1px solid #aaccaa; background: #aaccaa; ">
            ПРИ СОЗДАНИИ/РЕДАКТИРОВАНИИ РАСПИСАНИЙ:
            <li>для рабочих дней нужно выбрать даты и рабочие часы, нажать кнопку "Установить";</li>
            <li>для нерабочих дней <big>нужно</big> выбрать даты и, не заполняя часы, нажать кнопку "Установить".</li>
        </div>
        <br><br>
        <b>Редактируемые даты:</b>
        <table>
            <tr>
                <td>
                    <div id='dates'></div>
                </td>
                <td>
                    <textarea style="width:350px; height:200px;" id="control_date" name="control_date"><?php
                                                                                                        if ($default_editing_dates) {
                                                                                                            echo implode(';', $default_editing_dates) . '.';
                                                                                                        }
                                                                                                        ?></textarea>
                </td>
            </tr>
        </table>
        <br>
        <b>Рабочие часы:</b>
        <table border="0" id="schedule">
            <tr>
                <?php
                for ($i = 0; $i <= 23; $i++) {
                    echo '<td style="font-weight:bold;background:#AAAAAA;" 
                title="с ' . sprintf("%02d", $i) . 'ч.00мин до ' . sprintf("%02d", $i + 1) . 'ч.00мин">
                ' . sprintf("%02d", $i) . '..' . sprintf("%02d", $i + 1) . '</td>';
                }
                ?>
            </tr>
            <tr>
                <?php
                for ($h = 0; $h < 24; $h++) {
                    echo '
                    <td style="font-weight:bold;background:#AAAAAA;text-align:center">
                    <input type="checkbox" name="working_hours[' . $h . ']" value="' . $h . '"></td>
                    ';
                }
                ?>
            </tr>
        </table>
        <br>
        <input type="submit" class="button-grey" name="set" value="Установить" title="Для существующего конкретного расписания рабочие часы заменятся, &#013;для несуществующего - определятся">
    </form>
</center>

<table id="table-repairer" class="table-repairer tigra" style="width:100%; text-align:center;"></table>
