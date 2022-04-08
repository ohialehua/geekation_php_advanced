<?php
  session_start();
  $mode = "input";
  $errmessage = array();
  if( isset($_POST["back"] ) && $_POST["back"] ){
    // 何もしない
  } else if( isset($_POST["confirm"] ) && $_POST["confirm"] ){
    if( !$_POST["fullname"] ){
      $errmessage[]= "名前を入力して下さい";
    } else if ( mb_strlen($_POST["fullname"]) > 10 ){
      $errmessage[]= "名前は10文字以内にして下さい";
    } 
    $_SESSION["fullname"] = htmlspecialchars($_POST["fullname"], ENT_QUOTES);
    // JavaScriptの記号を無害化
    // $_POST["fullname"]の値をこの関数を介して、変換された文字列をSESSION["fullname"]に入れている

    if( !$_POST["kana"] ){
      $errmessage[]= "フリガナを入力して下さい";
    } else if ( mb_strlen($_POST["kana"]) > 10 ){
      $errmessage[]= "フリガナは10文字以内にして下さい";
    }
    $_SESSION["kana"] = htmlspecialchars($_POST["kana"], ENT_QUOTES);
    
    if( !preg_match( '/^0[0-9]{9,10}\z/', $_POST["tel"] ) ) {
      $errmessage[]= "電話番号は0~9の数字でハイフンなしで入力してください。";
    }
    $_SESSION["tel"] = $_POST["tel"];

    // バリデーションに使う正規表現
    $pattern = "/^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+.+([a-zA-Z0-9._-]+)+$/";
      // 使える文字はアルファベット大文字小文字 (a~z, A~Z) 、数字 (0~9)、記号 (. _ -)
      // 文字列の最初の文字は、記号以外
      // ＠を入れる
      // ＠の後の最初の文字は、(.)以外
      // ＠の前後で、それぞれ2文字以上の文字列が存在する
      // ＠の後は「.」が一つ存在する
    if( !$_POST["email"] ){
      $errmessage[]= "メールアドレスを入力して下さい";
    } else if ( !preg_match($pattern, $_POST["email"] ) ) {
      $errmessage[]= "不正な形式のメールアドレスです。";
    }
    $_SESSION["email"] = htmlspecialchars($_POST["email"], ENT_QUOTES); 

    if( !$_POST["body"] ){
      $errmessage[]= "お問い合わせ内容を入力して下さい";
    }
    $_SESSION["body"] = htmlspecialchars($_POST["body"], ENT_QUOTES);

    // エラーメッセージがある場合は入力画面に遷移
    if( $errmessage ){
        $mode = 'input';
    } else {
        $mode = 'confirm';
    }
  } else if( isset($_POST["send"] ) && $_POST["send"] ){
    $mode = "send";
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
          <label>氏名</label>
          <input type="text" name="fullname" value="<?php echo $_SESSION["fullname"] ?>">
        </div>
        <div>
          <label>フリガナ</label>
          <input type="text" name="kana" value="<?php echo $_SESSION["kana"] ?>">
        </div>
        <div>
          <label>電話番号</label>
          <input type="tel" name="tel" placeholder="ハイフンなし" value="<?php echo $_SESSION["tel"] ?>">
        </div>
        <div>
          <label>メールアドレス</label>
          <input type="email" name="email" value="<?php echo $_SESSION["email"] ?>">
        </div>
        <div>
          <label>お問い合わせ内容</label>
          <textarea name="body" placeholder="こちらにお問い合わせ内容を入力してください。"><?php echo $_SESSION["body"] ?></textarea>
        </div>
        <div>
          <input type="submit" name="confirm" value="確認" class="button">
        </div>
      </form>
    <?php } else if( $mode == "confirm" ) { ?>
      <h1>確認画面</h1>
      <form action="./contact.php" method="post">
        <div>
          <label>氏名</label>
          <?php echo $_SESSION["fullname"] ?>
        </div>
        <div>
          <label>フリガナ</label>
          <?php echo $_SESSION["kana"] ?>
        </div>
        <div>
          <label>電話番号</label>
          <?php echo $_SESSION["tel"] ?>
        </div>
        <div>
          <label>メールアドレス</label>
          <?php echo $_SESSION["email"] ?>
        </div>
        <div>
          <label>お問い合わせ内容</label>
          <?php echo nl2br($_SESSION["body"]) ?>
        </div>
        <div>
          <input type="submit" name="back" value="戻る" />
          <input type="submit" name="send" value="送信" />
        </div>
      </form>
    <?php } else { ?>
      <h1>完了画面</h1>
      <h4>
        お問い合わせ内容を送信しました。<br>
        ありがとうございました。
      </h4>
      <form action="./">
        <div>
          <input type="submit" name="top" value="トップへ"/>
        </div>
      </form>
    <?php } ?>
    </body>
  </html>