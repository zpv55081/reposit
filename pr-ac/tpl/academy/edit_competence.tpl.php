<br>
<center><a class="form_fieldset_label">РЕДАКТОР</a>
  <form action="" method="post">
    <table id="list_table" style="text-align:center; max-width: 1440px;">
      <tr style="font-weight:bold;background:#AAAAAA;">
        <td>Должность</td>
        <td style="width:170px;" title="(типы работ)")>Способности <a href="?page=abilities"><img src="img/b_edit.png" title="Добавить новое наименование способности"></a></td>
        <td title="Номер очереди компетенции">№оч.</td>
        <td title="(умения)">Компетенции <a href="?page=courses"><img src="img/b_edit.png" title="Добавить новое наименование компетенции"></a>
        </td>
        <td>Описание</td>
        <td>Редактирование</td>
      </tr>
      <?= $editable_and_another_competencies_html_rows; ?>
    </table>
  </form>
  <center><a href=academy.php><button class="btn btn-secondary btn-sm" style="cursor: pointer; width: 426px;">Вернуться в учебный центр</button></a>
    <br>
