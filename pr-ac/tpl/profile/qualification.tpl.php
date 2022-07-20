<br>
<center>
  <a href="profile.php?id=<?= $employee->id ?>">
    <button class="btn btn-secondary btn-sm" style="cursor: pointer; width: 444px;">
     <b> Профиль: <?= $employee->fio ?> </b>
    </button></a>


  <br>
  <center>
    <table id="list_table" style="text-align:center; width: 444px;">

      <tr style="font-weight:bold;background:#AAAAAA;">
        <td colspan="2">Пройденные курсы (компетенции)<a href="?page=edit_nom&nid={значение какое-то}"><img src="img/b_edit.png" title="Редактировать"></a></td>
      </tr>

      <tr>
        <td style="font-weight:bold;background:#AAAAAA;">Наименование курса</td>
        <td style="font-weight:bold;background:#AAAAAA;">Дата прохождения</td>
      </tr>

      <?= $rows_completed_competencies ?>

    </table>

    <br>

    <center>
      <table id="list_table" style="text-align:center; width: 444px;">

        <tr style="font-weight:bold;background:#AAAAAA;">
          <td colspan="2">Доступные курсы (непройденные)<a href="?page=edit_nom&nid={значение какое-то}"><img src="img/b_edit.png" title="Редактировать"></a></td>
        </tr>

        <tr>
          <td style="font-weight:bold;background:#AAAAAA;">Наименование курса</td>
          <td style="font-weight:bold;background:#AAAAAA;">Пройти</td>
        </tr>

        </tr>

        <?= $rows_available_competencies ?>

      </table>

      <?= "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\"Запросить обучение\" style='font-weight:bold;background:#6699FF;'></td></tr> <br>"; ?>
      <br>

      <center>
        <table id="list_table" style="text-align:center; width: 444px;">

          <tr style="font-weight:bold;background:#AAAAAA;">
            <td colspan="2">Недоступные курсы<a href="?page=edit_nom&nid={значение какое-то}"><img src="img/b_edit.png" title="Редактировать"></a></td>
          </tr>

          </tr>
          <tr>
            <td colspan="2" style="font-weight:bold;background:#AAAAAA;">Какое-то наименование курса</td>
          </tr>

          </tr>
          <tr>
            <td colspan="2" style="font-weight:bold;background:#AAAAAA;">Какое-то наименование курса</td>
          </tr>

        </table>
        <?php echo my_vidtab(1, "list_table"); ?>
        <br>
