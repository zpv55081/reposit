<center> <br>
    <p><b>ВЫБОР СПОСОБНОСТЕЙ (ТИПОВ РАБОТ) ДЛЯ СОТРУДНИКОВ</b></p>
<form action="../assign_abilities" method="GET">
   
        <table border="0" id="assign_abilities">
            <tr>
                <td style="font-weight:bold;background:#AAAAAA;" title="в систему б">Дата добавления</td>
                <td style="font-weight:bold;background:#AAAAAA;">ФИО</td>
                <td style="font-weight:bold;background:#AAAAAA;" title="трудоустройства">Город</td>
                <td style="font-weight:bold;background:#AAAAAA;">Должность</td>
                <?php
                //определям массив идентификторов и создаём заголовки столбцов способностей (типов работ)
                foreach ($abilities as $ability) {
                    $abilities_ids[] = $ability->id;
                    echo "<td style='font-weight:bold;background:#AAAAAA;'>$ability->name</td>";
                }
                ?>
            </tr>

            <?php
            if (!isset($profiles_assigned_abilities_array))
                die("По выбранным условиям нет сотрудников");
            //строчки сотрудников и галочки назначения способностей
            foreach ($profiles_assigned_abilities_array as $profile_abilities) {
                echo "
                    <tr>
                    <td>" . $profile_abilities['date_added'] . "</td> 
                    <td>" . $profile_abilities['fio'] . "</td>
                    <td>" . $profile_abilities['city_placement'] . "</td>
                    <td>" . $profile_abilities['department_post'] . "</td>
                    ";
                foreach ($abilities_ids as $k => $ability_id) {
                    if (in_array($ability_id, $profile_abilities['assigned_abilities_ids'])) {
                        echo "<td><input type='checkbox' name='aus_abilities[" . $profile_abilities['au_id'] . "][$k]' value='$ability_id' checked disabled readonly></td>";
                    } else {
                        echo "<td><input type='checkbox' name='aus_abilities[" . $profile_abilities['au_id'] . "][$k]' value='$ability_id' ></td>";
                    };
                }
                echo "</tr>";
            }
            ?>
        </table>
        <input type="submit" class="button-grey" name="assign_abilities" value="Назначить способности" title="Будьте внимательны, вы не сможете самостоятельно отменить назначение!">
</form>
<a href='../../../academy.php'><button class="button-grey" style="cursor: pointer; width: 300x;">Учебный центр</button></a>
<a href='../choose_abilities/profiles_filters_display'><button class="button-grey" style="cursor: pointer; width: 300x;">Выбор способностей</button></a>
<a href='../choose_competencies/profiles_filters_display'><button class="button-grey" style="cursor: pointer; width: 300x;">Выбор обучающих курсов</button></a>
</center>
<?php echo my_vidtab(0, 'assign_abilities');
require_once 'lv/t_b/adapt_down.php';
?>
