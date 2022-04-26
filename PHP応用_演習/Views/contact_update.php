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
      <main class="main pt-3">
        <div class="row">
          <div class="col">
          <h1>編集画面</h1>
        <?php
          $contact = $controller->show();
        ?>
          <form action="./contact.php?id=<?php echo $contact['id']; ?>" method="post">
            <div class="col-md-12" style="display:inline-flex">
              <div class="col-lg-6 mx-auto mt-2">
                <div>
                  <label>氏名</label><br>
                  <input type="text" name="fullname" id="inputName" value="<?php echo $contact['name'] ?>" style="width:50%;">
                </div>
                <div>
                  <label>フリガナ</label><br>
                  <input type="text" name="kana" id="inputKana" value="<?php echo $contact["kana"] ?>" style="width:50%;">
                </div>
                <div>
                  <label>電話番号</label><br>
                  <input type="tel" name="tel" id="inputTel" value="<?php echo $contact["tel"] ?>" style="width:50%;">
                </div>
                <div>
                  <label>メールアドレス</label><br>
                  <input type="email" name="email" id="inputEmail" value="<?php echo $contact["email"] ?>" style="width:80%;">
                </div>
              </div>
              <div class="col-lg-6">
                <div style="text-align: center;">
                  <div class="col-7 col-lg-4">
                    <label>お問い合わせ内容</label><br>
                  </div>
                  <textarea name="body" id="inputBody" class="p-2" style="width: 90%;" rows="10"><?php echo $contact["body"] ?></textarea>
                </div>
              </div>
            </div>
            <div class="my-5">
              <h4 class="text-center">
                上記の内容で編集いたします。<br>
                よろしいですか？
              </h4>
            </div>
            <div class="text-center mb-5">
              <input type="submit" name="back" value="キャンセル" class="btn btn-sm btn-primary mr-2" />
              <input type="submit" id="btnUpdate" name="update" value="更新" class="btn btn-sm btn-secondary ml-2"/>
            </div>
          </form>
          </div>
        </div>
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