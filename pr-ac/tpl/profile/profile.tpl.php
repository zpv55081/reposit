<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/form.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap-fix.css">

<style>
  .layout {
    max-width: 1600px;
    margin: auto;
  }

  .header {
    text-align: center;
  }

  .content {
    text-align: center;
  }
</style>

<div class="layout">

  <div class="header">

    <h4> Карточка сотрудника <small> (профиль в ) </small> </h4>

    <hr>

  </div>

  <div class="content">

    <div style="width: 244px; display: inline-block; vertical-align: top;">

      <table id="list_table" style="text-align:center; width: 150px;">
        <tr style="font-weight:bold;background:#AAAAAA;">
          <td><img src="img/photo.png"><a href="?page=edit_nom&nid={значение какое-то}"><img src="img/b_edit.png" title="Изменить фото"></a> </td>
        </tr>

        <tr>
          <td style="font-weight:bold;background:#AACCAA;">Кластеры</td>
        </tr>

        <?= $clusters_html_table ?>

      </table>

      <br>

      <table id="list_table" style="text-align:center; width: 150px;">

        <tr>
          <td style="font-weight:bold;background:#AACCAA;">Города<a href="dostup.php?login=<?= $employee->login ?>&submit=Города"><img src="img/b_edit.png" title="Редактировать города"></a></td>
        </tr>

        <?= $cities_html_table ?>

      </table>

    </div>

    <div style="display: inline-block;">

      <table id="list_table" style="text-align:center; width: 444px;">

        <tr style="font-weight:bold;background:#AAAAAA;">
          <td colspan="2">Данные о сотруднике<a href="dostup.php?login=<?= $employee->login ?>&submit=Редактировать"><img src="img/b_edit.png" title="Редактировать данные о сотруднике"></a></td>
        </tr>

        <tr>
          <td style="font-weight:bold;background:#AACCAA;">ФИО</td>
          <td style="font-weight:bold;background:#f9c66a;"><?php echo $employee->fio ?></td>
        </tr>

        <tr>
          <td style="font-weight:bold;background:#AACCAA;">Устроен<a href="?page=organizations"><img src="img/b_edit.png" title="Добавить новое наименование огранизации"></a></td>
          <td style="font-weight:bold;background:#f9c66a;"><?= $employee->organization?></td>
        </tr>

        <tr>
          <td style="font-weight:bold;background:#AACCAA;">Должность</td>
          <td style="font-weight:bold;background:#f9c66a;"><?= $employee->department_post?></td>
        </tr>

        <tr>
          <td style="font-weight:bold;background:#AACCAA;">Телефон</td>
          <td style="font-weight:bold;background:#f9c66a;"><?php echo $employee->phone ?></td>
        </tr>

        <tr>
          <td style="font-weight:bold;background:#AACCAA;">Email</td>
          <td style="font-weight:bold;background:#f9c66a;"><?php echo $employee->email ?></td>
        </tr>

      </table>

      <br>

      <table id="list_table" style="text-align:center; width: 444px;">

        <tr style="font-weight:bold;background:#AAAAAA;">
          <td colspan="2">Компетенции</td>
        </tr>

        <tr>
          <td style="font-weight:bold;background:#AACCAA;">Наименование курса</td>
          <td style="font-weight:bold;background:#AACCAA;">Дата прохождения<a href="?page=edit_nom&nid={значение какое-то}"><img src="img/b_edit.png" title="Редактировать дату прохождения"></a></td>
        </tr>

        <?=$rows_completed_competencies?>

        <?=$rows_available_competencies?>

      </table>
      <?php echo my_vidtab(1, "list_table"); ?>

      <br>

      <a href="?id=<?= $employee->id ?>&page=qualification"><button class="btn btn-secondary btn-sm" style="cursor: pointer; width: 426px;">Пройти обучение</button></a>

    </div>
    <br>
