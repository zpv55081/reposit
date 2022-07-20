<br>
<center>
    <p><b>ВЫБОР ОБУЧАЮЩИХ КУРСОВ</b></p>
<?php
foreach ($p_w_d_c as $prof_compet) {
    $cur_id_profile = $prof_compet['profile_id'];
    echo '
<br>
<center>
<form action="../assign_competencies" method="GET">
    <table id="'.$cur_id_profile.'" style="text-align:center; width: 888px;">
        <tr>
            <td style="background:#AAAAAA;" colspan="3"><b>'.$prof_compet['fio'].'</b> тел. <b>'.$prof_compet['phone'].'</b></td>
        </tr>
        <tr>
            <td style="background:#AAAAAA;">Город трудоустройства</td>
            <td style="background:#AAAAAA;">Дата добавления в б</td>
            <td style="background:#AAAAAA;">Должность</td>
        </tr>
        <tr>
            <td style="font-weight:bold;background:#AAAAAA;">'.$prof_compet['city_placement'].'</td>
            <td style="font-weight:bold;background:#AAAAAA;">'.$prof_compet['date_added'].'</td>
            <td style="font-weight:bold;background:#AAAAAA;">'.$prof_compet['department_post'].'</td>
        </tr>
        ';
        if (!empty($prof_compet['competencies']['started'])) {
                echo '
            <tr>
            <td colspan="3" style="font-weight:bold;background:#AAAAAA;">Назначенные курсы</td>
            </tr>
            <tr>
            <td style="background:#AAAAAA;">Способности (тип работ)</td>
            <td colspan="2" style="background:#AAAAAA;">Компетенции</td>
            </tr>
            ';
            foreach ($prof_compet['competencies']['started'] as $started_compet) {
                echo '
                <tr>
                    <td>'.$started_compet->ability_name.'</td>
                    <td colspan="2" title="'.$started_compet->description.'">'.$started_compet->course_name.'</td>
                </tr>
                ';
            }
        }
        if (!empty($prof_compet['competencies']['available'])) {
            echo '
            <tr>
            <td colspan="3" style="font-weight:bold;background:#AAAAAA;">Доступные курсы</td>
            </tr>
            <tr>
            <td style="background:#AAAAAA;">Способности (тип работ)</td>
            <td style="background:#AAAAAA;">Компетенции</td>
            <td style="background:#AAAAAA; width: 150px">Выбрать</td>
            </tr>
            ';
            foreach ($prof_compet['competencies']['available'] as $available_compet) {
                if (in_array($available_compet->id, $prof_compet['requested_abilities_ids'])) {
                    $is_requested = ' title="Обучение было запрошено профайлом!">запрошено! ';
                    $is_checked = 'checked>';
                } else {
                    $is_requested = '>';
                    $is_checked = '>';
                }
                echo '
                <tr>
                    <td>'.$available_compet->ability_name.'</td>
                    <td title="'.$available_compet->description.'">'.$available_compet->course_name.'</td>
                    <td'.$is_requested.'<input type="checkbox" name="competencies_ids['.
                    $available_compet->id.']" value="assign"'.$is_checked.'</td>
                </tr>
                ';
            }
        }
        echo '
        <tr>
            <td style="background:#AAAAAA;" colspan="3"> 
            Дополнительное уведомление на Email:<input type="email" name="additional_email">
            <input type="hidden" name="au_email" value="'.$prof_compet['email'].'">
            <input type="hidden" name="au_fio" value="'.$prof_compet['fio'].'">
            <input type="hidden" name="au_id" value="'.$prof_compet['au_id'].'">
            <input type="submit" class="button-grey" name="assign_abilities" value="Назначить сотруднику обучение по компетенциям" 
            title="Будьте внимательны, вы не сможете самостоятельно отменить назначение!">
            </td>
        </tr>
    </table>
</form>
<br>
    ';
    echo my_vidtab(1, "$cur_id_profile");
}
?>
<a href='../../../academy.php'><button class="button-grey" style="cursor: pointer; width: 300x;">Учебный центр</button></a>
<a href='../choose_abilities/profiles_filters_display'><button class="button-grey" style="cursor: pointer; width: 300x;">Выбор способностей</button></a>
<a href='../choose_competencies/profiles_filters_display'><button class="button-grey" style="cursor: pointer; width: 300x;">Выбор обучающих курсов</button></a>
</center>

<?php require_once 'lv/t_b/adapt_down.php'; ?>
