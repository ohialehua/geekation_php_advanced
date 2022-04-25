<?php
require_once(ROOT_PATH .'database.php');

class Contact extends Database {

  public function validate() {
    $errmessage = array();
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
    $pattern = "/^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+[.]([a-zA-Z0-9._-]+)+$/";
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
    return $errmessage;
  }

    public function __construct($dbh = null) {
        parent::__construct($dbh);
    }
    /**
     * contactsテーブルからすべてデータを取得
     */
    public function findAll():Array {
        $sql = 'SELECT * FROM contacts ORDER BY created_at DESC';
        $sth = $this->dbh -> prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getData() {
      $stmt = $this->dbh -> prepare('SELECT * FROM contacts WHERE id = :id');
      $id = $_GET['id'];
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result;
    }

    public function insert() {
      try{
        $this->dbh -> exec
        ('INSERT INTO contacts SET
            name = "'.$_SESSION["fullname"].'",
            kana = "'.$_SESSION["kana"].'",
            tel = "'.$_SESSION["tel"].'",
            email = "'.$_SESSION["email"].'",
            body = "'.$_SESSION["body"].'",
            created_at = NOW()'
        );
        $_SESSION["body"] = null;
      }catch (PDOException $e){
        print('Error:'.$e->getMessage());
        die();
      }
    }

    public function update() {
      try{
        $stmt = $this->dbh -> prepare('UPDATE contacts SET
            name = "'.$_POST["fullname"].'",
            kana = "'.$_POST["kana"].'",
            tel = "'.$_POST["tel"].'",
            email = "'.$_POST["email"].'",
            body = "'.$_POST["body"].'"
            WHERE id = :id');
        $id = $_REQUEST['id'];
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
      }catch (PDOException $e){
        print('Error:'.$e->getMessage());
        die();
      }
    }

    public function delete() {
      try{
        $stmt = $this->dbh -> prepare('DELETE FROM contacts WHERE id = :id');
        $id = $_REQUEST['id'];
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
      }catch (PDOException $e){
        print('Error:'.$e->getMessage());
        die();
      }
    }
  }