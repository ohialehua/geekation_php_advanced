<?php
  session_start();
  require_once(ROOT_PATH .'database.php');
  require_once(ROOT_PATH .'Controllers/ContactController.php');
  $dbh = new Database();
  $controller = new ContactController();
?>
<h1>編集画面</h1>
        <?php
          $contact = $controller->show();
          $controller->update();
        ?>
      <form action="./contact.php" method="post">
        <div>
          <label>氏名</label><br>
          <?php echo $contact['name'] ?>
        </div>
        <div>
          <label>フリガナ</label><br>
          <?php echo $contact["kana"] ?>
        </div>
        <div>
          <label>電話番号</label><br>
          <?php echo $contact["tel"] ?>
        </div>
        <div>
          <label>メールアドレス</label><br>
          <?php echo $contact["email"] ?>
        </div>
        <div>
          <label>お問い合わせ内容</label><br>
          <?php echo nl2br($contact["body"]) ?>
        </div>
        <div>
          <input type="submit" name="back" value="戻る" />
          <input type="submit" name="send" value="編集" />
        </div>
      </form>