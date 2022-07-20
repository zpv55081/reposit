<br>
<center>
    <form action="" method="post">
        <a class="form_fieldset_label">УЧЕБНЫЙ ЦЕНТР</a>
        <table id="list_table" style="text-align:center; max-width: 1440px;">
            <tr style="font-weight:bold;background:#AAAAAA;">
                <td>Должность</td>
                <td style="width:170px;" title="(типы работ)")>Способности <a href="?page=abilities"><img src="img/b_edit.png" title="Добавить новое наименование способности"></a></td>
                <td style="width:70px;" title="Номер очереди компетенции">№оч.</td>
                <td title="(умения)">Компетенции <a href="?page=courses"><img src="img/b_edit.png" title="Добавить новое наименование компетенции"></a>
                </td>
                <td>Описание</td>
                <td>Редактирование</td>
            </tr>
            <?= $all_competencies_html_rows; ?>
            <tr style="font-weight:bold;background:#AAAAAA;">
                <td><?= $maker_competence->department_posts_list ?></td>
                <td><?= $maker_competence->abilities_list ?></td>
                <td><input type="number" min="0" max="99" step="0.1" name="que" size="4" style="width:60px;" required=""> </td>
                <td><?= $maker_competence->courses_list ?></td>
                <td><input type="text" name="description" id="description" style="width:300px;"></td>
                <td rowspan="2">
                    <input type="hidden" name="page" value="add_competence" />
                    <button type="submit">Добавить<br>компетенцию</button>
                </td>
            </tr>
            <tr style="font-weight:bold;background:#AAAAAA;">
                <td colspan="5">
                    Периодичность курса в днях:
                    <input type="number" min="0" name="periodicity" style="width:60px;">
                    Ссылка на учебный курс:
                    <input type="text" name="course_link" id="course_link" style="width:380px;">
                    Готовность курса
                    <input type="checkbox" name="ready" value="1">
                </td>
            </tr>
        </table>
    </form>
  <a href=lv/academy/choose_abilities/profiles_filters_display>
    <button class="btn btn-secondary btn-sm" style="cursor: pointer; width: 300x;">
      Выбор способностей <br> для сотрудников
    </button>
  <a href=lv/academy/choose_competencies/profiles_filters_display>
    <button class="btn btn-secondary btn-sm" style="cursor: pointer; width: 300x;">
      Выбор компетенций <br> для сотрудников
    </button>
  <a href=academy.php?page=trainable_personnel>
    <button class="btn btn-secondary btn-sm" style="cursor: pointer; width: 300x;">
      Персонал на обучение
    </button>
  <a href=academy.php?page=make_changes>
    <button class="btn btn-secondary btn-sm" style="cursor: pointer; width: 300x;">
      Внести изменения по обученным
    </button>
  </a>
