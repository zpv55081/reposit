<br>
<form method="GET">
  <input type="hidden" name="page" value="trainable_personnel">
  <center><b>ПЕРСОНАЛ НА ОБУЧЕНИЕ<br><br></b>
    Должность:
    <select name='department_post' class='department_post'>
      <option value='NULL'>Не выбран</option>";
      <?php
      foreach ($post as $void => $department_post_row) {
        echo "<option value='{$department_post_row['id']}'";
        echo ">{$department_post_row['name']}</option>";
      }
      ?>
    </select>
  </center>
  <div style="width:290px;margin:auto;text-align:left;">
    <p>Сортировка по:</p>
    <input name="sort_by" type="radio" value="profile_date_added" checked> дате добавления сотрудника</p>
    <p><input name="sort_by" type="radio" value="course_name"> по компетенции</p>
    <p><input name="sort_by" type="radio" value="profile_placement_name"> по городу</p>
    <input type="submit" class="button-grey" name="generate" value="Сформировать">
  </div>
</form>
<center><table id="list_table" style="text-align:center; max-width: 1440px;">
<?=$rows_trainable_personnel?>
</table>
<br>
<center>
  <a href=academy.php>
    <button class="btn btn-secondary btn-sm" style="cursor: pointer; width: 426px;">
      Вернуться в учебный центр
    </button>
  </a>
  <br>
