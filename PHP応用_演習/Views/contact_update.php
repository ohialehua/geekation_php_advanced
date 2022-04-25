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
        ?>
      <form action="./contact.php?id=<?php echo $contact['id']; ?>" method="post">
        <div>
          <label>氏名</label><br>
          <input type="text" name="fullname" value="<?php echo $contact['name'] ?>">
        </div>
        <div>
          <label>フリガナ</label><br>
          <input type="text" name="kana" value="<?php echo $contact["kana"] ?>">
        </div>
        <div>
          <label>電話番号</label><br>
          <input type="tel" name="tel" value="<?php echo $contact["tel"] ?>">
        </div>
        <div>
          <label>メールアドレス</label><br>
          <input type="email" name="email" value="<?php echo $contact["email"] ?>">
        </div>
        <div>
          <label>お問い合わせ内容</label><br>
          <textarea name="body" cols="30" rows="10"><?php echo nl2br($contact["body"]) ?></textarea>
        </div>
        <div>
          <h4>
            上記の内容で編集いたします。<br>
            よろしいですか？
          </h4>
        </div>
        <div>
          <input type="submit" name="back" value="キャンセル" />
          <input type="submit" name="update" value="更新" />
        </div>
      </form>