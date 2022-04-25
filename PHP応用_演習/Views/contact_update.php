<?php
  session_start();
  require_once(ROOT_PATH .'database.php');
  require_once(ROOT_PATH .'Controllers/ContactController.php');
  $dbh = new Database();
  $controller = new ContactController();
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
      <main class="main">
      <h1>編集画面</h1>
        <?php
          $contact = $controller->show();
        ?>
      <form action="./contact.php?id=<?php echo $contact['id']; ?>" method="post">
        <div>
          <label>氏名</label><br>
          <input type="text" name="fullname" id="inputName" value="<?php echo $contact['name'] ?>">
        </div>
        <div>
          <label>フリガナ</label><br>
          <input type="text" name="kana" id="inputKana" value="<?php echo $contact["kana"] ?>">
        </div>
        <div>
          <label>電話番号</label><br>
          <input type="tel" name="tel" id="inputTel" value="<?php echo $contact["tel"] ?>">
        </div>
        <div>
          <label>メールアドレス</label><br>
          <input type="email" name="email" id="inputEmail" value="<?php echo $contact["email"] ?>">
        </div>
        <div>
          <label>お問い合わせ内容</label><br>
          <textarea name="body" id="inputBody" cols="30" rows="10"><?php echo nl2br($contact["body"]) ?></textarea>
        </div>
        <div>
          <h4>
            上記の内容で編集いたします。<br>
            よろしいですか？
          </h4>
        </div>
        <div>
          <input type="submit" name="back" value="キャンセル" />
          <input type="submit" id="btnUpdate" name="update" value="更新"/>
        </div>
      </form>
      <script>
        window.onload = function(){
        /*各画面オブジェクト*/
          const btnUpdate = document.getElementById('btnUpdate');
          const inputName = document.getElementById('inputName');
          const inputKana = document.getElementById('inputKana');
          const inputTel = document.getElementById('inputTel');
          const inputEmail = document.getElementById('inputEmail');
          const inputBody = document.getElementById('inputBody');
          const reg = /^0[0-9]{9,10}$/;
          const pattern = /^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+[.][a-zA-Z0-9._-]+$/;

          btnUpdate.addEventListener('click', function(event) {
            let message = [];
            /*入力値チェック*/
            if(inputName.value ==""){
                message.push("名前を入力して下さい");
            } else if(inputName.value.length>10) {
                message.push("名前は10文字以内にして下さい");
            }
            if(inputKana.value==""){
                message.push("フリガナを入力して下さい");
            } else if(inputKana.value.length>10) {
                message.push("フリガナは10文字以内にして下さい");
            }
            if(inputTel.value==""){
                message.push("電話番号を入力して下さい");
            } else if(!reg.test(inputTel.value)){
                message.push("不正な形式の電話番号です。");
            }
            if(inputEmail.value==""){
                message.push("メールアドレスを入力してください");
            }else if(!pattern.test(inputEmail.value)){
                message.push("不正な形式のメールアドレスです。");
            }
            if(inputBody.value==""){
                message.push("お問い合わせ内容を入力してください");
            }
            if(message.length > 0){
                alert(message);
                return;
            }
          });
        };
      </script>
      </main>
      <footer>
        <?php require_once('footer.php'); ?>
      </footer>
    </body>
  </html>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.7/js/swiper.min.js"></script>