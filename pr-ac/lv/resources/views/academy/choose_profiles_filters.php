<br>
<center>
    <p><b><?= $headline ?></b></p>
    <form action=<?= $form_action ?> method="get" name="choose_profiles_filters">
        <table border="0" id="choose_profile_filters">
            <tr>
                <td>Дата добавления сотрудника в систему б:</td>
                <td>
                    с <input type="date" name="profile_addition_date_start" min="2010-01-01" max="2050-01-01">
                    по <input type="date" name="profile_addition_date_finish" min="2010-01-01" max="2050-01-01">
                </td>
            </tr>

            <tr>
                <td>
                    Город трудоустройства:
                </td>
                <td>
                    <select name='city_placement_id' class='placement'>
                        <option value='ignore'>Не учитывать</option>";
                        <option value='NULL'>Не указан</option>";
                        <?php
                        foreach ($arrgroup as $key => $placement_name) {
                            echo "<option value='{$key}'";
                            echo ">{$placement_name}</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>

            <tr>
                <td>
                    Должность:
                </td>
                <td>
                    <select name='department_post_id' class='department_post'>
                        <option value='ignore'>Не учитывать</option>";
                        <option value='NULL'>Не указана</option>";
                        <?php
                        foreach ($posts as $void => $department_post_row) {
                            echo "<option value='{$department_post_row['id']}'";
                            echo ">{$department_post_row['name']}</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>

        </table>
        <input type="submit" class="button-grey" name="choose_abilities" value="Отобразить список">
    </form>
    <a href='../../../academy.php'><button class="button-grey" style="cursor: pointer; width: 300x;">Учебный центр</button></a>
    <a href='../choose_abilities/profiles_filters_display'><button class="button-grey" style="cursor: pointer; width: 300x;">Выбор способностей</button></a>
    <a href='../choose_competencies/profiles_filters_display'><button class="button-grey" style="cursor: pointer; width: 300x;">Выбор обучающих курсов</button></a>
</center>
<?php echo my_vidtab(0, 'choose_profile_filters');
require_once "lv/t_b/adapt_down.php";
?>
