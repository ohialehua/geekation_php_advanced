<?php
require_once(ROOT_PATH .'database.php');

class Contact extends Database {
    public function __construct($dbh = null) {
        parent::__construct($dbh);
    }
    /**
     * contactsテーブルからすべてデータを取得（20件ごと）
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
      $id = $_REQUEST['id'];
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
}