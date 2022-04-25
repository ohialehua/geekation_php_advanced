<?php
  session_start();
  require_once(ROOT_PATH .'database.php');
  require_once(ROOT_PATH .'Controllers/ContactController.php');
  $dbh = new Database();
  $controller = new ContactController();
  $model = new Contact();
  $mode = "input";
  $errmessage = null;
  if( isset($_POST["back"] ) && $_POST["back"] ){
    // 何もしない
  } else if( isset($_POST["confirm"] ) && $_POST["confirm"] ){
    $errmessage = $model->validate();
    // エラーメッセージがある場合は入力画面に遷移
    if( $errmessage ){
        $mode = 'input';
    } else {
        $mode = 'confirm';
    }
  } else if( isset($_POST["send"] ) && $_POST["send"] ){
    $mode = "send";
  } else if( isset($_POST["update"] ) && $_POST["update"] ){
    $mode = "update";
  } else {
    $SESSION = array();
    // GETで来た時用にセッションを初期化する
  }
?>
<!DOCTYPE html>
  <html lang="ja">
    <head>
      <meta charset="UTF-8">
    </head>
    <body>
    <?php if( $mode == "input" ){ ?>
      <h1>入力画面</h1>
      <?php
        if( $errmessage ){
          echo '<div class="alert alert-danger" role="alert">';
          echo implode('<br>', $errmessage );
          echo '</div>';
        }
      ?>
      <form action="./contact.php" method="post">
        <div>
          <label>氏名</label><br>
          <input type="text" name="fullname" value="<?php if($_SESSION) {echo $_SESSION["fullname"];} ?>">
        </div>
        <div>
          <label>フリガナ</label><br>
          <input type="text" name="kana" value="<?php if($_SESSION) {echo $_SESSION["kana"];} ?>">
        </div>
        <div>
          <label>電話番号</label><br>
          <input type="tel" name="tel" placeholder="ハイフンなし" value="<?php if($_SESSION) {echo $_SESSION["tel"];} ?>">
        </div>
        <div>
          <label>メールアドレス</label><br>
          <input type="email" name="email" value="<?php if($_SESSION) {echo $_SESSION["email"];} ?>">
        </div>
        <div>
          <label>お問い合わせ内容</label><br>
          <textarea name="body" cols="30" rows="10"  placeholder="こちらにお問い合わせ内容を入力してください。"><?php if($_SESSION) {echo $_SESSION["body"];} ?></textarea>
        </div>
        <div>
          <input type="submit" name="confirm" value="確認" class="button">
        </div>
      </form>
      <div>
        <table>
          <thead>
            <td>日時</td>
            <td>氏名</td>
            <td>フリガナ</td>
            <td>電話番号</td>
            <td>メールアドレス</td>
            <td>お問い合わせ内容</td>
          </thead>
          <tbody>
            <?php
              $contacts = $controller->index();
              foreach ($contacts as $contact) {
            ?>
              <tr>
                <td><?php echo date('Y年n/j', strtotime($contact["created_at"])) ?></td>
                <td><?php echo $contact["name"] ?></td>
                <td><?php echo $contact["kana"] ?></td>
                <td><?php echo $contact["tel"] ?></td>
                <td><?php echo $contact["email"] ?></td>
                <td><?php echo $contact["body"] ?></td>
                <td>
                  <button><a href="contact_update.php?id=<?php echo $contact['id']; ?>">編集</a></button>
                </td>
              </tr>
            <?php } ?>
            <tr></tr>
          </tbody>
        </table>
      </div>
    <?php } else if( $mode == "confirm" ) { ?>
      <h1>確認画面</h1>
      <form action="./contact.php" method="post">
        <div>
          <label>氏名</label><br>
          <?php echo $_SESSION["fullname"] ?>
        </div>
        <div>
          <label>フリガナ</label><br>
          <?php echo $_SESSION["kana"] ?>
        </div>
        <div>
          <label>電話番号</label><br>
          <?php echo $_SESSION["tel"] ?>
        </div>
        <div>
          <label>メールアドレス</label><br>
          <?php echo $_SESSION["email"] ?>
        </div>
        <div>
          <label>お問い合わせ内容</label><br>
          <?php echo nl2br($_SESSION["body"]) ?>
        </div>
        <div>
          <input type="submit" name="back" value="戻る" />
          <input type="submit" name="send" value="送信" />
        </div>
      </form>
    <?php } else if( $mode == "send" ) { ?>
      <h1>完了画面</h1>
      <?php
        $controller->create();
        // $controller->update();
      ?>
      <h4>
        お問い合わせ内容を送信しました。<br>
        ありがとうございました。
      </h4>
      <form action="./">
        <div>
          <input type="submit" name="top" value="トップへ"/>
        </div>
      </form>
    <?php } else { ?>
      <h1>更新完了画面</h1>
      <?php
        $controller->update();
      ?>
      <h4>
        内容の更新を完了しました。<br>
        ありがとうございました。
      </h4>
      <form action="contact.php">
        <div>
          <input type="submit" name="input" value="入力画面へ"/>
        </div>
      </form>
    <?php } ?>
    </body>
  </html>