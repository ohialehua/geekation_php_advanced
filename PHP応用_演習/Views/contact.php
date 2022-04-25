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
    $SESSION = array();
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
        <div class="container px-0">
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
                if( $_SESSION["flash"] ){
                  echo $_SESSION["flash"];
                  $_SESSION["flash"] = null;
                }
              ?>
              <form action="./contact.php" method="post" style="width: 100%;">
                <div class="col-12" style="display:inline-flex">
                  <div class="col-5 mt-2">
                    <div>
                      <label>氏名</label><br>
                      <input type="text" name="fullname" value="<?php if($_SESSION) {echo $_SESSION["fullname"];} ?> " style="width:80%;">
                    </div>
                    <div>
                      <label>フリガナ</label><br>
                      <input type="text" name="kana" value="<?php if($_SESSION) {echo $_SESSION["kana"];} ?>" style="width:80%;">
                    </div>
                    <div>
                      <label>電話番号</label><br>
                      <input type="tel" name="tel" placeholder="ハイフンなし" value="<?php if($_SESSION) {echo $_SESSION["tel"];} ?>" style="width:80%;">
                    </div>
                    <div>
                      <label>メールアドレス</label><br>
                      <input type="email" name="email" value="<?php if($_SESSION) {echo $_SESSION["email"];} ?>" style="width:100%;">
                    </div>
                  </div>
                  <div class="col-6 offset-1">
                    <div>
                      <label>お問い合わせ内容</label><br>
                      <textarea name="body" cols="50" rows="10"  placeholder="こちらにお問い合わせ内容を入力してください。"><?php if($_SESSION) {echo $_SESSION["body"];} ?></textarea>
                    </div>
                  </div>
                </div>
                <div class="col-1 offset-10 my-2 text-right">
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
                    <td colspan="3"><?php echo $contact["body"] ?></td>
                    <td>
                      <button><a href="contact_update.php?id=<?php echo $contact['id']; ?>">編集</a></button>
                    </td>
                    <td>
                      <form action="./contact.php?id=<?php echo $contact['id']; ?>" method="post">
                        <input type="submit" name="delete" value="削除" onclick="return confirm('本当に削除しますか？')">
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
          <?php
          } else if($mode == "update") {
              $controller->update();
              $mode = "input";
              $_SESSION["flash"] = "内容の更新が完了しました。";
              header("Location:contact.php");
          } else {
              $controller->destroy();
              $mode = "input";
              $_SESSION["flash"] = "内容の削除に成功しました。";
              header("Location:contact.php");
          }
          ?>
        </div>
      </main>
      <footer>
        <?php require_once('footer.php'); ?>
      </footer>
    </body>
  </html>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.7/js/swiper.min.js"></script>