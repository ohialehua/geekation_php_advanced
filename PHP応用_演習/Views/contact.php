<?php
  session_start();
  require_once(ROOT_PATH .'database.php');
  require_once(ROOT_PATH .'Controllers/ContactController.php');
  $dbh = new Database();
  $controller = new ContactController();
  $model = new Contact();
  $mode = "input";
  $errmessage = null;
  // $flash = null;
  if( isset($_POST["back"] ) && $_POST["back"] ){
  } else if( isset($_POST["confirm"] ) ){
    $errmessage = $model->validate();
    // エラーメッセージがある場合は入力画面に遷移
    if( $errmessage ){
      $mode = 'input';
    } else {
      $mode = 'confirm';
    }
  } else if( isset($_POST["send"] ) ){
      $mode = "send";
  } else if( isset($_POST["update"] ) ){
    $errmessage = $model->validate();
    if( $errmessage ){
        header("Location:".$_SERVER['HTTP_REFERER']);
    } else {
      $mode = 'update';
    }
  } else if( isset($_POST["delete"] ) ){
      $mode = "delete";
  } else {
    $_SESSION = array();
    // GETで来た時用にセッションを初期化する
  }
?>
<!DOCTYPE html>
  <html lang="ja">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Casteria</title>
      <link rel="stylesheet" type="text/css" href="../css/base.css">
      <link rel="stylesheet" type="text/css" href="../css/style.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.7/css/swiper.min.css" />
      <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
      <script defer src="../js/index.js"></script>
    </head>
    <body>
      <header>
        <?php require_once('header.php'); ?>
      </header>
      <main class="main pt-3">
        <div class="row">
          <div class="col">
          <?php if( $mode == "input" ){ ?>
            <h1>入力画面</h1>
            <?php
              if( $errmessage ){
                echo '<div class="alert alert-danger" role="alert">';
                echo implode('<br>', $errmessage );
                echo '</div>';
              }
              // if( $flash ){
              //   echo '<div class="alert alert-success" role="alert">';
              //   echo $flash;
              //   echo '</div>';
              //   $flash = null;
              // }
            ?>
            <form action="./contact.php" method="post" style="width: 100%;">
              <div class="col-md-12" style="display:inline-flex">
                <div class="col-lg-6 mx-auto mt-2">
                  <div>
                    <label>氏名</label><br>
                    <input type="text" name="fullname" value="<?php if($_SESSION) {echo $_SESSION["fullname"];} ?>" placeholder="例) 田中太郎" style="width:50%;">
                  </div>
                  <div>
                    <label>フリガナ</label><br>
                    <input type="text" name="kana" value="<?php if($_SESSION) {echo $_SESSION["kana"];} ?>" placeholder="例) タナカタロウ" style="width:50%;">
                  </div>
                  <div>
                    <label>電話番号</label><br>
                    <input type="tel" name="tel" value="<?php if($_SESSION) {echo $_SESSION["tel"];} ?>" placeholder="ハイフンなし" style="width:50%;">
                  </div>
                  <div>
                    <label>メールアドレス</label><br>
                    <input type="email" name="email" value="<?php if($_SESSION) {echo $_SESSION["email"];} ?>" placeholder="xxxx@xxx.xx" style="width:80%;">
                  </div>
                </div>
                <div class="col-lg-6">
                  <div style="text-align: center;">
                    <div class="col-7 col-lg-4">
                      <label>お問い合わせ内容</label><br>
                    </div>
                    <textarea name="body" placeholder="こちらにお問い合わせ内容を入力してください。" class="p-2" style="width: 90%;" rows="10"><?php if($_SESSION) {echo $_SESSION["body"];} ?></textarea>
                  </div>
                </div>
              </div>
              <div class="col-2 offset-10 my-2 pr-5 text-right">
                <input type="submit" name="confirm" value="確認" class="button">
              </div>
            </form>
          </div>
          <div class="col-12">
            <table class="table table-danger">
              <thead>
                <td>日時</td>
                <td>
                  <label>フリガナ</label><br>
                  氏名
                </td>
                <td>電話番号</td>
                <td>メールアドレス</td>
                <td colspan="3">お問い合わせ内容</td>
                <td></td>
              </thead>
              <tbody>
              <?php
                $contacts = $controller->index();
                foreach ($contacts as $contact) {
              ?>
                <tr>
                  <td><?php echo date('Y年n/j', strtotime($contact["created_at"])) ?></td>
                  <td>
                    <label><?php echo $contact["kana"] ?></label><br>
                    <?php echo $contact["name"] ?>
                  </td>
                  <td><?php echo $contact["tel"] ?></td>
                  <td><?php echo $contact["email"] ?></td>
                  <td colspan="3"><?php echo nl2br($contact["body"]) ?></td>
                  <td>
                    <div class="mb-3">
                      <a href="contact_update.php?id=<?php echo $contact['id']; ?>" class="btn btn-sm btn-secondary">編集</a>
                    </div>
                    <form action="./contact.php?id=<?php echo $contact['id']; ?>" method="post">
                      <input type="submit" name="delete" value="削除" onclick="return confirm('本当に削除しますか？')" class="btn btn-sm btn-danger">
                    </form>
                  </td>
                </tr>
              <?php } ?>
                <tr></tr>
              </tbody>
            </table>
          </div>
        </div>
        <?php } else if( $mode == "confirm" ) { ?>
          <h1>確認画面</h1>
          <div class="col-6 offset-3">
            <form action="./contact.php" method="post">
              <div class="text-center bg-light border">
                <div class="border-bottom my-3 pb-2">
                  <strong><label>氏名</label></strong><br>
                  <?php echo $_SESSION["fullname"] ?>
                </div>
                <div class="border-bottom mb-3 pb-2">
                  <strong><label>フリガナ</label></strong><br>
                  <?php echo $_SESSION["kana"] ?>
                </div>
                <div class="border-bottom mb-3 pb-2">
                  <strong><label>電話番号</label></strong><br>
                  <?php echo $_SESSION["tel"] ?>
                </div>
                <div class="border-bottom mb-3 pb-2">
                  <strong><label>メールアドレス</label></strong><br>
                  <?php echo $_SESSION["email"] ?>
                </div>
                <div class="border-bottom pb-2">
                  <strong><label>お問い合わせ内容</label></strong><br>
                  <?php echo nl2br($_SESSION["body"]) ?>
                </div>
              </div>
              <div class="text-center my-3">
                <input type="submit" name="back" value="戻る" class="btn btn-sm btn-info mr-2" />
                <input type="submit" name="send" value="送信" onclick="return confirm('こちらの内容で送信しますか？')" class="btn btn-sm btn-success ml-2" />
              </div>
            </form>
          </div>
        <?php } else if( $mode == "send" ) {
          $controller->create();
          $_SESSION = array();
          session_destroy();
          if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
          }
        ?>
          <h1>完了画面</h1>
          <div class="col-8 offset-2">
            <div>
            <h4 class="text-center my-5">
              お問い合わせ内容を送信しました。<br>
              ありがとうございました。
            </h4>
            <form action="./">
              <div class="text-center">
                <input type="submit" name="top" value="トップへ" class="btn btn-sm btn-primary mb-5"/>
              </div>
            </form>
          </div>
        </div>
          <?php
          } else if($mode == "update") {
              $controller->update();
              $_SESSION = array();
              session_destroy();
              if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time() - 3600, '/');
              }
              $mode = "input";
              // $flash = "内容の更新が完了しました。";
              header("Location:contact.php");
          } else {
              $controller->destroy();
              $_SESSION = array();
              session_destroy();
              if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time() - 3600, '/');
              }
              $mode = "input";
              // $flash = "内容の削除に成功しました。";
              header("Location:contact.php");
          }
          ?>
      </main>
      <footer>
        <?php require_once('footer.php'); ?>
      </footer>
    </body>
  </html>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.7/js/swiper.min.js"></script>